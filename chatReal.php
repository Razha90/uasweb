<?php
require 'vendor/autoload.php';
require '/database/database.sqlite';

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Server\IoServer; // Pastikan ini diimpor
use Ratchet\Http\HttpServer; // Pastikan ini diimpor
use Ratchet\WebSocket\WsServer; // Pastikan ini diimpor

class Chat implements MessageComponentInterface
{
  protected $clients;
  private $db;
  private $logger;

  public function __construct()
  {
    // Koneksi ke database SQLite
    $dbPath = __DIR__ . '/database/database.sqlite'; // Sesuaikan jalur jika perlu
    $this->db = new SQLite3($dbPath);
    $this->logger = new Logger();

    // Cek koneksi
    if (!$this->db) {
      die("Koneksi gagal: " . $this->db->lastErrorMsg());
    }

    $this->clients = new \SplObjectStorage;
  }

  public function onOpen(ConnectionInterface $conn)
  {
    $this->clients->attach($conn);
  }

  public function onMessage(ConnectionInterface $from, $msg)
  {
    // Kirim pesan ke semua klien kecuali pengirim
    foreach ($this->clients as $client) {
      if ($from !== $client) {
        $client->send($msg);
      }
    }

    // Simpan pesan ke database
    $this->saveMessageToDB($msg);
  }

  public function onClose(ConnectionInterface $conn)
  {
    $this->clients->detach($conn);
  }

  public function onError(ConnectionInterface $conn, \Exception $e)
  {
    $conn->close();
  }

  private function getUsers(): array
  {
    try {
      $sql = "SELECT * FROM users WHERE role = 'admin' LIMIT 1";
      $users = $this->db->prepare($sql);
      $users->execute();

      // Ambil hasil dari query
      $result = $users;

      // Jika hasil tidak kosong, kembalikan array pertama, jika kosong kembalikan null
      return !empty($result) ? $result : [];
    } catch (\Throwable $th) {
      $this->logger->error($th->getMessage());
      return []; // Kembalikan null jika terjadi kesalahan
    }
  }


  private function saveMessageToDB($msg)
  {
    try {
      $data = json_decode($msg);
      $getUsers = $this->getUsers();
      // Pastikan sender_id dan receiver_id ada di dalam data
      if (isset($data->receiver_id) && isset($data->message)) {
        $stmt = $this->db->prepare("INSERT INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?)");
        $stmt->bindValue(1, $data->sender_id, SQLITE3_TEXT);
        $stmt->bindValue(2, $getUsers->id, SQLITE3_TEXT);
        $stmt->bindValue(3, $data->message, SQLITE3_TEXT);
        $stmt->execute();
      }
    } catch (\Throwable $th) {
      $this->logger->error($th->getMessage());}}
}

// Jalankan server WebSocket
$server = IoServer::factory(
  new HttpServer(
    new WsServer(
      new Chat() // Pastikan Chat di sini
    )
  ),
  8082
);

$server->run();
