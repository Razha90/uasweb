<?php
if (isset($_SESSION['display_name'])) {
  $logged_in = true;
  $id = $_SESSION['id'];
  $display_name = $_SESSION['display_name'];
  $username = $_SESSION['username'];
  $role = $_SESSION['role'] == 'admin';
} else {
  $logged_in = false;
  $role = false;
}

$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
$host = $_SERVER['HTTP_HOST'];
$uri = $_SERVER['REQUEST_URI'];
$fullUrl = "$protocol://$host$uri";
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Belajar Online</title>
  <link rel="stylesheet" href="/css/app.css">
  <link rel="stylesheet" href="/css/chatting.css">

  <link rel="icon" href="/img/Icon-Perpustakaan.png" type="image/png">

</head>
<script src="/js/vue.global.js"></script>
<!-- <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script> -->

<style>
  .hidden {
    display: none;
  }

  .rotate-90 {
    transform: rotate(90deg);
    transition: transform 0.3s;
  }

  .rotate-0 {
    transform: rotate(0deg);
    transition: transform 0.3s;
  }

  .alert {
    position: absolute;
    top: 10px;
    right: 10px;
    transform: translateY(-100%);
    animation: slideIn 0.3s ease forwards;
    display: none;
  }

  .alert.show {
    display: block;
  }

  @keyframes slideIn {
    from {
      transform: translateY(-100%);
    }

    to {
      transform: translateY(0);
    }
  }

  /* .line-clamp-3 {
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
} */
</style>

<body>
  <div id="app">
    <div v-if="isScore" class="fixed inset-0 bg-gray-700 bg-opacity-50 backdrop-blur-sm z-40"></div>
    <div v-if="isScore" class="absolute flex justify-center items-center w-[100vw] h-[100vh]">
      <div class="p-10 bg-white rounded-lg shadow-lg text-center w-80 z-50">
        <h2 class="text-2xl font-bold text-gray-800">Kamu Telah Menyelesaikan Kelas <br> {{title}}</h2>
        <p class="mt-4 text-4xl font-extrabold text-blue-600">{{score}}</p>
        <button v-on:click="back"
          class="mt-6 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">Kembali</button>
      </div>
    </div>
    <div id="alertError"
      class="alert p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
      <span class="font-medium">Gagal!</span> Data Quiz Baru Gagal Ditambahkan.
    </div>
    <header class="w-full h-[20vh] min-h-[184px]">
      <div class="w-full h-20 py-4 flex justify-center items-center border-b-2 border-b-gray-900">
        <h1 class="text-3xl font-bold">SELAMAT DATANG DI PEMBELAJARAN ONLINE</h1>
      </div>
      <div class="bg-sky-950 p-5">
        <nav class="w-full flex justify-between items-center">
          <button v-on:click="back"
            class="text-white flex flex-row bg-blue-700 items-center hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
              width="24" height="24" fill="none" viewBox="0 0 24 24">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M5 12h14M5 12l4-4m-4 4 4 4" />
            </svg>
            <p class="text-xl">Back</p>
          </button>
          <?php if ($logged_in): ?>
            <div id="nav-button"
              class="flex flex-row justify-center items-center gap-3 bg-indigo-500 rounded-xl py-3 px-6 cursor-pointer">
              <p class="text-2xl text-white font-bold">Selamat Datang, <span class="italic"><?= $display_name ?></span>
              </p>
              <div class="relative flex justify-center items-center">
                <button id="dropdownButton" class="focus:outline-none bg-indigo-800 rounded-xl p-2">
                  <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                    width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="m19 9-7 7-7-7" />
                  </svg>
                </button>
                <div id="dropdownMenu"
                  class="hidden absolute right-0 top-8 mt-2 w-48 bg-white rounded-md drop-shadow-lg py-2 z-50">
                  <a href="/logout" class="block px-4 py-2 text-gray-800 hover:bg-gray-200">Keluar</a>
                </div>
              </div>
            </div>
          <?php endif; ?>
        </nav>
      </div>
    </header>
    <main class="container mx-auto h-[77vh] min-h-[400px]">
      <div class="w-full pt-4">
        <h2 class="text-center text-4xl font-extrabold">{{title}}</h2>
      </div>
      <div class="w-full flex flex-row mt-4">
        <div class="w-[80%]">
          <div v-for="data in dataQuiz" :key="data.order_number">
            <div v-if="data.show.order_number == String(currentState)" v-html="data.show.question"
              class="text-3xl mt-3 mb-3">
            </div>
            <div v-if="data.show.order_number == String(currentState)">
              <div class="flex flex-row items-center">
                <input type="radio" :id="`option1-${data.show.order_number}`" name="option" v-model="data.show.choose"
                  :value="data.show.pilihan1" class="w-[25px] h-[25px] mr-3">
                <label class="text-2xl" :for="`option1-${data.show.order_number}`">{{ data.option1 }}</label>
              </div>
              <div class="flex flex-row items-center mt-2">
                <input type="radio" :id="`option2-${data.show.order_number}`" name="option" v-model="data.show.choose"
                  :value="data.show.pilihan2" class="w-[25px] h-[25px] mr-3">
                <labe class="text-2xl" l :for="`option2-${data.show.order_number}`">{{ data.option2 }}</label>
              </div>
              <div class="flex flex-row items-center mt-2">
                <input type="radio" :id="`option3-${data.show.order_number}`" name="option" v-model="data.show.choose"
                  :value="data.show.pilihan3" class="w-[25px] h-[25px] mr-3">
                <label class="text-2xl" :for="`option3-${data.show.order_number}`">{{ data.option3 }}</label>
              </div>
              <div class="flex flex-row items-center mt-2">
                <input type="radio" :id="`option4-${data.show.order_number}`" name="option" v-model="data.show.choose"
                  :value="data.show.pilihan4" class="w-[25px] h-[25px] mr-3">
                <label class="text-2xl" :for="`option4-${data.show.order_number}`">{{ data.option4 }}</label>
              </div>

            </div>
          </div>
          <div class="flex flex-row gap-4 w-full justify-center mt-5">
            <button v-if="currentState != 1" v-on:click="changeState(currentState-1)"
              class="bg-slate-400 rounded-lg p-5 text-white font-bold mt-5">Sebelumnya</button>
            <button v-if="dataQuiz.length != currentState" v-on:click="changeState(currentState+1)"
              class="bg-slate-400 rounded-lg p-5 text-white font-bold mt-5">Selanjutnya</button>
            <button v-else v-on:click="saveQuiz"
              class="bg-pink-300 rounded-lg p-5 text-white font-bold mt-5">Submit</button>

          </div>
        </div>
        <div class="w-[20%] border-l-4 border-slate-500-500 pl-2">
          <h2 class="text-2xl font-bold text-center mb-2">Pilih Quiz</h2>
          <div class="flex flex-row flex-wrap gap-3">
            <div v-for="data in dataQuiz">
              <button v-on:click="changeState(parseInt(data.order_number, 10))"
                class="rounded-lg p-5 text-white font-bold" :class="{'bg-blue-600':currentState == parseInt(data.order_number, 10),
              'bg-gray-500':currentState != parseInt(data.order_number, 10)}">
                {{data.order_number}}
              </button>
            </div>
          </div>
        </div>
      </div>
    </main>
    <div class="h-6"></div>

  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      let get_back = document.getElementById('back-button');
      let navButton = document.getElementById('nav-button');
      var dropdownButton = document.getElementById('dropdownButton');
      var dropdownMenu = document.getElementById('dropdownMenu');

      navButton.addEventListener('click', function () {
        dropdownMenu.classList.toggle('hidden');
      });

      window.addEventListener('click', function (event) {
        if (!dropdownButton.contains(event.target) && !dropdownMenu.contains(event.target)) {
          dropdownMenu.classList.add('hidden');
        }
      });

      get_back.addEventListener('click', function () {
        const currentDomain = window.location.hostname;
        const referrer = document.referrer;

        if (referrer) {
          const referrerDomain = (new URL(referrer)).hostname;
          if (referrerDomain === currentDomain) {
            window.history.back();
          } else {
            window.location.href = window.location.protocol + "//" + currentDomain;
          }
        } else {
          window.location.href = window.location.protocol + "//" + currentDomain;
        }
      });

    });
  </script>
  <script>
    const {
      createApp,
      ref,
      reactive,
      onMounted,
      onBeforeUnmount,
      toRaw
    } = Vue

    createApp({
      setup() {
        const dataQuiz = reactive([]);
        const dataId = ref('');
        const title = ref('');
        const currentState = ref(1);
        const questionId = ref(1);
        const sendData = reactive([]);
        const score = ref(0);
        const isScore = ref(false);

        async function getScore() {
          const datas = {
            user_id: '<?= $id ?>',
            quiz_id: dataId.value,
          };
          fetch(`/api/score`, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
            },
            body: JSON.stringify(datas),
          })
            .then((response) => response.json())
            .then((data) => {
              if (data.status === 'success') {
                score.value = data.data.score;
                isScore.value = true;
              }
            })
            .catch((error) => {
              console.error('Error:', error);
            });
        }

        async function getQuizById() {
          const path = window.location.pathname;
          const segments = path.split('/');
          const id = segments[2];

          try {
            const response = await fetch(`/api/quiz/${id}`);
            const data = await response.json();

            if (data.status === 'success') {
              title.value = data.data.title;
              dataId.value = data.data.id; // pastikan dataId.value diisi di sini
              questionId.value = data.data.questions.length + 1;

              dataQuiz.splice(0, dataQuiz.length); // reset dataQuiz sebelum memasukkan data baru

              data.data.questions.forEach((quiz) => {
                dataQuiz.push({
                  ...quiz,
                  show: {
                    question: quiz.question,
                    option1: quiz.option1,
                    option2: quiz.option2,
                    option3: quiz.option3,
                    option4: quiz.option4,
                    pilihan1: 'option1',
                    pilihan2: 'option2',
                    pilihan3: 'option3',
                    pilihan4: 'option4',
                    choose: '',
                    order_number: quiz.order_number,
                  },
                });
              });

              console.log('Quiz data:', data);
            } else {
              showAlert();
              setTimeout(() => {
                window.location.href = '/';
              }, 3000);
            }
          } catch (error) {
            console.error('Error:', error);
            showAlert();
            setTimeout(function () {
              window.location.href = '/';
            }, 3000);
          }
        }

        onMounted(async () => {
          await getQuizById(); // Tunggu getQuizById selesai
          await getScore();    // Lalu panggil getScore setelah dataId.value terisi
        });
        function showAlert() {
          const alertElement = document.getElementById('alertError');
          alertElement.classList.add('show');
          setTimeout(function () {
            alertElement.classList.remove('show');
          }, 3000);
        }

        function checkData() {
          for (let i = 0; i < dataQuiz.length; i++) {
            const quiz = dataQuiz[i].show;
            if (quiz.choose === '') {
              alert('Kamu Belum Menyelesaikan Quiz!');
              return false;
            }
          }

          return true;
        }

        async function saveQuiz() {
          if (checkData()) {
            let stateScore = 0;
            dataQuiz.forEach(data => {
              if (data.show.choose === data.answer) {
                stateScore += 1;
              }
            });
            score.value = Math.round((stateScore / dataQuiz.length) * 100);

            fetch(`/api/score/add`, {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
              },
              body: JSON.stringify({
                user_id: '<?= $id ?>',
                quiz_id: dataId.value,
                score: score.value
              })
            })
              .then((response) => response.json())
              .then((data) => {
                if (data.status === 'success') {
                  isScore.value = true;
                } else {
                  showAlert();
                }
              })
              .catch((error) => {
                console.error('Error:', error);
                showAlert();
              });
            sendData.splice(0, sendData.length);
            dataQuiz.forEach(data => {
              sendData.push({
                ...data.answer
              });
            });
            // fetch('/api/quiz/update/' + dataId.value, {
            //   method: 'POST',
            //   headers: {
            //     'Content-Type': 'application/json',
            //   },
            //   body: JSON.stringify(
            //     {
            //       title: title.value,
            //       id: questionId.value,
            //       data: toRaw(sendData),
            //     }
            //   ),
            // })
            //   .then((response) => response.json())
            //   .then((data) => {
            //     if (data.status === 'success') {
            //       alert('Data Quiz Berhasil Ditambahkan!');
            //     } else {
            //       showAlert();
            //     }
            //   })
            //     .catch((error) => {
            //       console.error('Error:', error);
            //       showAlert();
            //     });
          }
        }

        function changeState(state) {
          currentState.value = state;
        }

        function back() {
          window.location.href = '/';
        }


        return {
          dataQuiz,
          saveQuiz,
          title,
          dataId,
          currentState,
          changeState,
          score,
          isScore,
          back
        }
      }
    }).mount('#app')
  </script>
</body>

</html>