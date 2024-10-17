<?php
require_once __DIR__ . '/../model/message.php';
require_once __DIR__ . '/../database/logger.php';

final class messageController
{
  private $logger;

  public function __construct()
  {
    $this->logger = new Logger();
  }
  public function addMessage()
  {
    $messages = new Message();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $message = $_POST['message'] ?? '';
      $sender_id = $_POST['sender_id'] ?? '';
      $receiver_id = $_POST['receiver_id'] ?? '';
      $created_at = date('Y-m-d H:i:s');

      if (empty($message) || empty($sender_id) || empty($receiver_id)) {
        file_put_contents(__DIR__ . '/../logs/app.log', sprintf("[%s][%s] %s\n", date('Y-m-d H:i:s'), '[ERROR]', 'ERROR ADD MESSAGE'), FILE_APPEND);
        header("Location: {$_SERVER['HTTP_REFERER']}?status=error");
        return;
      }

      $isMessageAdded = $messages->addMessage($message, $sender_id, $receiver_id, $created_at);

      if ($isMessageAdded) {
        header("Location: {$_SERVER['HTTP_REFERER']}?status=success");
      } else {
        file_put_contents(__DIR__ . '/../logs/app.log', sprintf("[%s][%s] %s\n", date('Y-m-d H:i:s'), '[ERROR]', 'ERROR ADD MESSAGE'), FILE_APPEND);
        header("Location: {$_SERVER['HTTP_REFERER']}?status=error");
      }
    } else {
      file_put_contents(__DIR__ . '/../logs/app.log', sprintf("[%s][%s] %s\n", date('Y-m-d H:i:s'), '[ERROR]', 'ERROR ADD MESSAGE'), FILE_APPEND);
      header("Location: {$_SERVER['HTTP_REFERER']}?status=error");
    }
  }

  public function getMessage($id)
  {
    $logger = new Logger();

    $messages = new Message();
    header('Content-Type: application/json');

    $messageData = $messages->getMessageUser($id);
    if (empty($messageData)) {
      echo json_encode([
        'status' => 'error',
        'message' => 'Data pesan tidak ditemukan.'
      ]);
    } else {
      $logger->warning('Data pesan ditemukan, menampilkan data pesan.');
      echo json_encode([
        'status' => 'success',
        'data' => $messageData
      ]);
    }
  }

  public function getUser($id)
  {
    $logger = new Logger();

    $messages = new Message();
    header('Content-Type: application/json');

    $messageData = $messages->getUser($id);
    if (empty($messageData)) {
      echo json_encode([
        'status' => 'error',
        'message' => 'Data pesan tidak ditemukan.'
      ]);
    } else {
      $logger->warning('Data pesan ditemukan, menampilkan data pesan.');
      echo json_encode([
        'status' => 'success',
        'data' => $messageData
      ]);
    }
  }

  public function getReveiverAndSender($receiver_id, $sender_id)
  {
    $logger = new Logger();

    $messages = new Message();
    header('Content-Type: application/json');

    $messageData = $messages->getMessageReceive($receiver_id, $sender_id);
    if (empty($messageData)) {
      echo json_encode([
        'status' => 'error',
        'message' => 'Data pesan tidak ditemukan.'
      ]);
    } else {
      $logger->warning('Data pesan ditemukan, menampilkan data pesan.');
      echo json_encode([
        'status' => 'success',
        'data' => $messageData
      ]);
    }
  }

}