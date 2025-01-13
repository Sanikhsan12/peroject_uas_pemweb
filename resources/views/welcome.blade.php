<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
    <!-- link CDn Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
  </head>
  <body class="h-screen bg-black">
    <!-- navbar start -->
    <nav
      class="fixed navbar bg-orange-950 p-4 w-full flex justify-between items-center bg-opacity-65 z-50"
    >
      <div class="container-judul text-2xl">
        <p class="font-bold text-white hover:text-orange-500">
          Aplikasi Perpustakaan.
        </p>
      </div>
      <div class="container-item">
        <ul class="flex space-x-4 text-white">
          <li class="hover:text-orange-500"><a href="#home">Home</a></li>
          <li class="hover:text-orange-500"><a href="#about">About</a></li>
          <li class="hover:text-orange-500"><a href="#books">Books</a></li>
          <li class="hover:text-orange-500"><a href="#contact">contact</a></li>
        </ul>
      </div>
    </nav>
    <!-- navbar end -->

    <!-- Home Section Start-->
    <div
      class="bg-[url('{{ asset('asset/welcome_page.jpg') }}')] text-center flex flex-col items-center pt-64  bg-cover bg-center bg-no-repeat h-full relative"
      id="home"
    >
      <h1 class="text-center text-6xl font-semibold text-white py-4">
        Selamat Datang Di Aplikasi Peminjaman Buku
      </h1>
      <p
        class="text-center text-lg font-semibold text-neutral-50 mb-6 px-3 py-3"
      >
        Kami menyediakan berbagai buku yang dapat Anda pinjam. Mulai dengan
        login untuk melanjutkan.
      </p>
      <div class="flex justify-center">
        <a href="/login">
          <button
            class="px-6 py-3 bg-orange-950 text-white rounded-full text-lg hover:bg-orange-700 transition duration-300"
          >
            Get Started
          </button>
        </a>
      </div>
      <!-- Blending Start-->
      <div
        class="absolute bottom-0 left-0 right-0 h-32 bg-gradient-to-b from-transparent to-black pointer-events-none"
      ></div>
      <!-- Blending End -->
    </div>
    <!-- Home Section End -->

    <!-- About Section Start -->
    <div
      class="text-center flex flex-col items-center justify-center pb-32 pt-32"
      id="about"
    >
      <div class="container-about-judul mt-32">
        <h2 class="text-center text-white p-4 font-bold text-2xl">
          Tentang Kami
        </h2>
      </div>
      <div class="container-about-content p-4">
        <p class="text-center text-white mx-80 text-lg">
          Kami adalah perpustakaan yang didedikasikan untuk menyediakan berbagai
          jenis buku yang dapat dipinjam oleh masyarakat luas. Dengan koleksi
          yang beragam mulai dari buku pelajaran, fiksi, non-fiksi, hingga
          referensi akademik, kami berkomitmen untuk memenuhi kebutuhan literasi
          semua kalangan.
        </p>
        <p class="text-center text-white mx-80 text-lg">
          Dengan visi menjadi pusat pembelajaran dan pengetahuan yang unggul,
          kami terus berinovasi dalam memberikan layanan terbaik kepada
          pengunjung. Kami juga menyelenggarakan berbagai kegiatan edukatif dan
          literasi, seperti diskusi buku, lokakarya, dan pameran. Tujuan kami
          adalah untuk mendorong minat baca dan mendukung pendidikan bagi semua
          usia. Kami percaya bahwa dengan memberikan akses yang mudah dan luas
          ke sumber daya pendidikan, kami bisa membantu menciptakan masyarakat
          yang lebih cerdas dan berpengetahuan.
        </p>
      </div>
    </div>
    <!-- About Section End -->

    <!-- Books Section Start -->
    <div class="flex flex-col items-center justify-center" id="books">
      <div class="books-judul text-2xl font-bold text-white p-4 pt-36">
        Buku
      </div>
      <p class="text-center text-xl text-white">
        Berikut adalah contoh beberapa buku yang tersedia di perpustakaan kami
      </p>
      <div
        class="container-buku grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 max-w-7xl mx-auto p-4"
      >
        <!-- card1 -->
        <div
          class="card bg-orange-950 rounded-lg flex flex-col items-center p-4"
        >
          <img
            src="{{ asset('asset/supernova_ksatria.jpg') }}"
            alt=""
            class="rounded-lg w-full h-auto object-cover"
          />
          <p class="text-center text-white text-xl pt-6">
            Supernova ksatria putri dan bintang jatuh
          </p>
          <ul class="text-white self-start mt-4 space-y-2">
            <li>
              Penulis:
              <span class="hover:text-orange-500">
                <a href="https://id.wikipedia.org/wiki/Dewi_Lestari"
                  >Dewi Lestari</a
                >
              </span>
            </li>
            <li>
              Penerbit:
              <span class="hover:text-orange-500">
                <a href="https://bentangpustaka.com/">Bentang</a>
              </span>
            </li>
            <li>Tahun terbit: 2001</li>
          </ul>
        </div>
        <!-- card2 -->
        <div
          class="card bg-orange-950 rounded-lg flex flex-col items-center p-4"
        >
          <img
            src="{{ asset('asset/supernova_akar.jpg') }}"
            alt=""
            class="rounded-lg w-full h-auto object-cover"
          />
          <p class="text-center text-white text-xl pt-6">Supernova Akar</p>
          <ul class="text-white self-start mt-4 space-y-2">
            <li>
              Penulis:
              <span class="hover:text-orange-500">
                <a href="https://id.wikipedia.org/wiki/Dewi_Lestari"
                  >Dewi Lestari</a
                >
              </span>
            </li>
            <li>
              Penerbit:
              <span class="hover:text-orange-500">
                <a href="https://bentangpustaka.com/">Bentang</a>
              </span>
            </li>
            <li>Tahun terbit: 2002</li>
          </ul>
        </div>
        <!-- card3 -->
        <div
          class="card bg-orange-950 rounded-lg flex flex-col items-center p-4"
        >
          <img
            src="{{ asset('asset/tereliye_bulan.jpg') }}"
            alt=""
            class="rounded-lg w-full h-auto object-cover"
          />
          <p class="text-center text-white text-xl pt-6">Tereliye Bulan</p>
          <ul class="text-white self-start mt-4 space-y-2">
            <li>
              Penulis:
              <span class="hover:text-orange-500">
                <a href="https://id.wikipedia.org/wiki/Tere_Liye">Tere Liye</a>
              </span>
            </li>
            <li>
              Penerbit:
              <span class="hover:text-orange-500">
                <a href="https://gpu.id/">Gramedia Pustaka Utama</a>
              </span>
            </li>
            <li>Tahun terbit: 2015</li>
          </ul>
        </div>
        <!-- card4 -->
        <div
          class="card bg-orange-950 rounded-lg flex flex-col items-center p-4"
        >
          <img
            src="{{ asset('asset/tereliye_bumi.jpg') }}"
            alt=""
            class="rounded-lg w-full h-auto object-cover"
          />
          <p class="text-center text-white text-xl pt-6">Tereliye Bumi</p>
          <ul class="text-white self-start mt-4 space-y-2">
            <li>
              Penulis:
              <span class="hover:text-orange-500">
                <a href="https://id.wikipedia.org/wiki/Tere_Liye">Tere Liye</a>
              </span>
            </li>
            <li>
              Penerbit:
              <span class="hover:text-orange-500">
                <a href="https://gpu.id/">Gramedia Pustaka Utama</a>
              </span>
            </li>
            <li>Tahun terbit: 2014</li>
          </ul>
        </div>
        <!-- card5 -->
        <div
          class="card bg-orange-950 rounded-lg flex flex-col items-center p-4"
        >
          <img
            src="{{ asset('asset/daniel_thinking.jpg') }}"
            alt=""
            class="rounded-lg w-full h-auto object-cover"
          />
          <p class="text-center text-white text-xl pt-6">
            Daniel Thingking , Fast And Slow
          </p>
          <ul class="text-white self-start mt-4 space-y-2">
            <li>
              Penulis:
              <span class="hover:text-orange-500">
                <a href="https://id.wikipedia.org/wiki/Daniel_Kahneman"
                  >Daniel Kahneman</a
                >
              </span>
            </li>
            <li>
              Penerbit:
              <span class="hover:text-orange-500">
                <a href="https://us.macmillan.com/fsg/">Farrar</a>
              </span>
            </li>
            <li>Tahun terbit: 2011</li>
          </ul>
        </div>
      </div>
    </div>
    <!-- Books Section End -->

    <!-- Contact Section Start -->
    <div class="flex flex-col items-center justify-center mt-48" id="contact">
      <div class="contact-judul text-2xl font-bold text-white p-4">
        Hubungi Kami
      </div>
      <div class="contact-content p-4 max-w-2xl">
        <p class="text-center text-white mx-auto text-lg pb-4">
          "Jika Anda memiliki pertanyaan atau butuh bantuan, jangan ragu untuk
          menghubungi kami melalui formulir di bawah ini.
        </p>
        <form
          action=""
          method="post"
          class="mt-4 justify-center items-center flex flex-col p-2"
        >
          <input
            type="text"
            placeholder="Masukan Nama Anda"
            name="input-nama"
            class="input bg-orange-950 rounded-lg text-white px-3 w-1/2 text-center mt-6"
          />
          <input
            type="text"
            name="input-email"
            placeholder="Masukan Email Anda"
            class="input bg-orange-950 rounded-lg text-white px-3 w-1/2 text-center mt-6"
          />
          <input
            type="text"
            class="input bg-orange-950 rounded-lg text-white px-3 w-1/2 text-center mt-6 h-20"
            placeholder="Masukan Subjek"
            name="input-subjek"
          />
          <div
            class="rounded-lg bg-orange-950 justify-center items-center p-2 mt-6"
          >
            <button class="text-white text-center w-10">Kirim</button>
          </div>
        </form>
      </div>
    </div>
    <!-- Contact Section End -->

    <!-- Footer Start -->
    <footer
      class="flex items-center justify-center bg-orange-950 text-white p-4 mt-32"
    >
      <p class="text-center">
        © 2025 Aplikasi Perpustakaan | Project Uas Pemrograman Web
      </p>
    </footer>
    <!-- Footer End -->
  </body>
</html>
