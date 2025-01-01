function openAddBookModal() {
    document.getElementById("bookModal").classList.remove("hidden");
    document.getElementById("bookForm").reset();
}

function openEditBookModal(book) {
    document.getElementById("bookModal").classList.remove("hidden");
    document.getElementById("bookId").value = book.id;
    document.getElementById("kategori_id").value = book.kategori_id;
    document.getElementById("judul").value = book.judul;
    document.getElementById("penulis").value = book.penulis;
    document.getElementById("penerbit").value = book.penerbit;
    document.getElementById("isbn").value = book.isbn;
    document.getElementById("tahun").value = book.tahun;
    document.getElementById("jumlah").value = book.jumlah;
}

function closeBookModal() {
    document.getElementById("bookModal").classList.add("hidden");
}

// CRUD functions
function addBook(formData) {
    fetch("/admin/books", {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
            "Content-Type": "application/json",
        },
        body: JSON.stringify(formData),
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                closeBookModal();
                loadBooks();
            }
        });
}

function updateBook(id, formData) {
    fetch(`/admin/books/${id}`, {
        method: "PUT",
        headers: {
            "X-CSRF-TOKEN": document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
            "Content-Type": "application/json",
        },
        body: JSON.stringify(formData),
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                closeBookModal();
                loadBooks();
            }
        });
}

function deleteBook(id) {
    if (confirm("Apakah anda yakin ingin menghapus buku ini?")) {
        fetch(`/admin/books/${id}`, {
            method: "DELETE",
            headers: {
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content"),
            },
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    loadBooks();
                }
            });
    }
}

// Load Books Table
function loadBooks() {
    const booksTable = document.getElementById("booksTable");
    booksTable.innerHTML = '<p class="text-gray-500">Memuat data buku...</p>';

    fetch("/admin/books")
        .then((response) => response.json())
        .then((books) => {
            booksTable.innerHTML = `
                <div class="mb-4">
                    <button onclick="openAddBookModal()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Tambah Buku
                    </button>
                </div>
                <table class="min-w-full bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
                    <thead class="bg-gray-100 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left">ID</th>
                            <th class="px-6 py-3 text-left">Kategori</th>
                            <th class="px-6 py-3 text-left">Judul</th>
                            <th class="px-6 py-3 text-left">Penulis</th>
                            <th class="px-6 py-3 text-left">Penerbit</th>
                            <th class="px-6 py-3 text-left">ISBN</th>
                            <th class="px-6 py-3 text-left">Tahun</th>
                            <th class="px-6 py-3 text-left">Jumlah</th>
                            <th class="px-6 py-3 text-left">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${books
                            .map(
                                (book) => `
                            <tr class="border-t border-gray-200 dark:border-gray-700">
                                <td class="px-6 py-4">${book.id}</td>
                                <td class="px-6 py-4">${
                                    book.kategori ? book.kategori.nama : "N/A"
                                }</td>
                                <td class="px-6 py-4">${book.judul}</td>
                                <td class="px-6 py-4">${book.penulis}</td>
                                <td class="px-6 py-4">${book.penerbit}</td>
                                <td class="px-6 py-4">${book.isbn}</td>
                                <td class="px-6 py-4">${book.tahun}</td>
                                <td class="px-6 py-4">${book.jumlah}</td>
                                <td class="px-6 py-4">
                                    <button onclick="openEditBookModal(${JSON.stringify(
                                        book
                                    ).replace(/"/g, "&quot;")})" 
                                        class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-1 px-2 rounded mr-2">
                                        Edit
                                    </button>
                                    <button onclick="deleteBook(${book.id})" 
                                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded">
                                        Hapus
                                    </button>
                                </td>
                            </tr>
                        `
                            )
                            .join("")}
                    </tbody>
                </table>
            `;
        })
        .catch((error) => {
            console.error("Error:", error);
            booksTable.innerHTML =
                '<p class="text-red-500">Error loading books data</p>';
        });
}

document.addEventListener("DOMContentLoaded", function () {
    // Sidebar Toggle
    const sidebar = document.getElementById("sidebar");
    const sidebarBtn = document.getElementById("sidebarToggle");

    sidebarBtn.addEventListener("click", function () {
        sidebar.classList.toggle("-translate-x-full");
    });

    // Dark Mode Toggle
    const darkModeBtn = document.getElementById("darkModeToogle");
    darkModeBtn.addEventListener("click", function () {
        document.documentElement.classList.toggle("dark");
        localStorage.theme = document.documentElement.classList.contains("dark")
            ? "dark"
            : "light";
    });

    // Check initial theme
    if (localStorage.theme === "dark") {
        document.documentElement.classList.add("dark");
    } else {
        document.documentElement.classList.remove("dark");
    }

    // Navigation Management
    const sections = {
        home: document.getElementById("homeContent"),
        books: document.getElementById("booksContent"),
        borrow: document.getElementById("borrowContent"),
        return: document.getElementById("returnContent"),
        history: document.getElementById("historyContent"),
    };

    const menu = {
        home: document.getElementById("menuHome"),
        books: document.getElementById("menuBooks"),
        borrow: document.getElementById("menuBorrow"),
        return: document.getElementById("menuReturn"),
        history: document.getElementById("menuHistory"),
    };

    function showContent(sectionName) {
        Object.values(sections).forEach((section) => {
            section.classList.add("hidden");
        });
        sections[sectionName].classList.remove("hidden");

        // Load content based on section
        switch (sectionName) {
            case "books":
                loadBooks();
                break;
            case "borrow":
                loadBorrows();
                break;
            case "return":
                loadReturns();
                break;
            case "history":
                loadHistory();
                break;
        }
    }

    Object.keys(menu).forEach((menuName) => {
        menu[menuName].addEventListener("click", (e) => {
            e.preventDefault();
            showContent(menuName);
        });
    });

    // Modal forms
    const bookModalHTML = `
    <div id="bookModal" class="fixed z-10 inset-0 hidden mt-12">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75"></div>
            <div class="bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full">
                <form id="bookForm" class="p-6">
                    <input type="hidden" id="bookId">
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="kategori_id">
                            Kategori
                        </label>
                        <input type="text" id="kategori_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="judul">
                            Judul
                        </label>
                        <input type="text" id="judul" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="penulis">
                            Penulis
                        </label>
                        <input type="text" id="penulis" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="penerbit">
                            Penerbit
                        </label>
                        <input type="text" id="penerbit" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="isbn">
                            ISBN
                        </label>
                        <input type="text" id="isbn" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="tahun">
                            Tahun
                        </label>
                        <input type="number" id="tahun" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="jumlah">
                            Jumlah
                        </label>
                        <input type="number" id="jumlah" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>
                    <div class="flex items-center justify-between">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Simpan
                        </button>
                        <button type="button" onclick="closeBookModal()" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    `;

    document.addEventListener("reloadBooks", function () {
        loadBooks();
    });

    // Add modal to document
    document.body.insertAdjacentHTML("beforeend", bookModalHTML);

    // Form submission
    document
        .getElementById("bookForm")
        .addEventListener("submit", function (e) {
            e.preventDefault();
            const formData = {
                kategori_id: document.getElementById("kategori_id").value,
                judul: document.getElementById("judul").value,
                penulis: document.getElementById("penulis").value,
                penerbit: document.getElementById("penerbit").value,
                isbn: document.getElementById("isbn").value,
                tahun: document.getElementById("tahun").value,
                jumlah: document.getElementById("jumlah").value,
            };

            const bookId = document.getElementById("bookId").value;
            if (bookId) {
                updateBook(bookId, formData);
            } else {
                addBook(formData);
            }
        });

    // Load Borrows Table
    function loadBorrows() {
        const borrowTable = document.getElementById("borrowTable");
        borrowTable.innerHTML =
            '<p class="text-gray-500">Memuat data peminjaman...</p>';

        fetch("/admin/borrows")
            .then((response) => response.json())
            .then((borrows) => {
                borrowTable.innerHTML = `
                    <table class="min-w-full bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
                        <thead class="bg-gray-100 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left">ID</th>
                                <th class="px-6 py-3 text-left">User</th>
                                <th class="px-6 py-3 text-left">Buku</th>
                                <th class="px-6 py-3 text-left">Tanggal Pinjam</th>
                                <th class="px-6 py-3 text-left">Tanggal Kembali</th>
                                <th class="px-6 py-3 text-left">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${borrows
                                .map(
                                    (borrow) => `
                                <tr class="border-t border-gray-200 dark:border-gray-700">
                                    <td class="px-6 py-4">${borrow.id}</td>
                                    <td class="px-6 py-4">${
                                        borrow.user.name
                                    }</td>
                                    <td class="px-6 py-4">${
                                        borrow.buku.judul
                                    }</td>
                                    <td class="px-6 py-4">${formatDate(
                                        borrow.tanggal_pinjam
                                    )}</td>
                                    <td class="px-6 py-4">${formatDate(
                                        borrow.tanggal_kembali
                                    )}</td>
                                    <td class="px-6 py-4">${borrow.status}</td>
                                </tr>
                            `
                                )
                                .join("")}
                        </tbody>
                    </table>
                `;
            })
            .catch((error) => {
                console.error("Error:", error);
                borrowTable.innerHTML =
                    '<p class="text-red-500">Error loading borrow data</p>';
            });
    }

    // Load Returns Table
    function loadReturns() {
        const returnTable = document.getElementById("returnTable");
        returnTable.innerHTML =
            '<p class="text-gray-500">Memuat data pengembalian...</p>';

        fetch("/admin/returns")
            .then((response) => {
                if (!response.ok) {
                    throw new Error("Network response was not ok");
                }
                return response.json();
            })
            .then((returns) => {
                if (!returns || returns.length === 0) {
                    returnTable.innerHTML =
                        '<p class="text-gray-500">Tidak ada data pengembalian</p>';
                    return;
                }

                returnTable.innerHTML = `
                    <table class="min-w-full bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
                        <thead class="bg-gray-100 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left">ID</th>
                                <th class="px-6 py-3 text-left">User</th>
                                <th class="px-6 py-3 text-left">Buku</th>
                                <th class="px-6 py-3 text-left">Tanggal Kembali</th>
                                <th class="px-6 py-3 text-left">Denda</th>
                                <th class="px-6 py-3 text-left">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${returns
                                .map((returnItem) => {
                                    const userName =
                                        returnItem.pinjam?.user?.name ||
                                        "User tidak tersedia";
                                    const bookTitle =
                                        returnItem.pinjam?.buku?.judul ||
                                        "Buku tidak tersedia";
                                    const returnDate =
                                        returnItem.tanggal_kembali || "-";
                                    const fine =
                                        typeof returnItem.denda === "number"
                                            ? returnItem.denda
                                            : 0;
                                    const status =
                                        returnItem.pinjam?.status || "";

                                    return `
                                    <tr class="border-t border-gray-200 dark:border-gray-700">
                                        <td class="px-6 py-4">${
                                            returnItem.id || "-"
                                        }</td>
                                        <td class="px-6 py-4">${userName}</td>
                                        <td class="px-6 py-4">${bookTitle}</td>
                                        <td class="px-6 py-4">${formatDate(
                                            returnDate
                                        )}</td>
                                        <td class="px-6 py-4">Rp ${fine.toLocaleString(
                                            "id-ID"
                                        )}</td>
                                        <td class="px-6 py-4">
                                            ${
                                                status !== "dikembalikan"
                                                    ? `<button 
                                                    onclick="handleReturnBook(${returnItem.pinjam?.id})"
                                                    class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 transition-colors">
                                                    Kembalikan
                                                </button>`
                                                    : '<span class="text-green-500">Sudah dikembalikan</span>'
                                            }
                                        </td>
                                    </tr>
                                `;
                                })
                                .join("")}
                        </tbody>
                    </table>
                `;
            })
            .catch((error) => {
                console.error("Error:", error);
                returnTable.innerHTML = `
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <strong class="font-bold">Gagal memuat data!</strong>
                        <span class="block sm:inline"> Silakan coba lagi nanti.</span>
                    </div>
                `;
            });
    }

    function handleReturnBook(pinjamId) {
        if (!confirm("Apakah Anda yakin ingin mengembalikan buku ini?")) {
            return;
        }

        fetch(`/admin/mark-returned/${pinjamId}`, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content"),
                "Content-Type": "application/json",
            },
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.status === "success") {
                    alert(data.message);
                    // Refresh tabel setelah pengembalian berhasil
                    loadReturns();
                } else {
                    alert("Gagal mengembalikan buku: " + data.message);
                }
            })
            .catch((error) => {
                console.error("Error:", error);
                alert("Terjadi kesalahan saat mengembalikan buku");
            });
    }

    // Load History
    function loadHistory() {
        const historyResults = document.getElementById("historyResults");
        historyResults.innerHTML =
            '<p class="text-gray-500">Memuat riwayat peminjaman...</p>';

        fetch("/admin/history")
            .then((response) => response.json())
            .then((data) => {
                historyResults.innerHTML = `
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-xl font-semibold mb-4 text-blue-950 dark:text-white">Buku yang Sedang Dipinjam</h3>
                            ${displayHistoryItems(data.borrowed, true)}
                        </div>
                        
                        <div>
                            <h3 class="text-xl font-semibold mb-4 text-blue-950 dark:text-white">Riwayat Pengembalian</h3>
                            ${displayHistoryItems(data.returned, false)}
                        </div>
                    </div>
                `;

                // Add event listeners for return buttons
                document
                    .querySelectorAll(".return-book-btn")
                    .forEach((button) => {
                        button.addEventListener("click", function () {
                            const pinjamId =
                                this.getAttribute("data-pinjam-id");
                            handleBookReturn(pinjamId);
                        });
                    });
            })
            .catch((error) => {
                console.error("Error:", error);
                historyResults.innerHTML =
                    '<p class="text-red-500">Error loading history data</p>';
            });
    }

    function displayHistoryItems(items, showReturnButton) {
        if (!items || items.length === 0) {
            return '<p class="text-gray-500">Tidak ada data</p>';
        }

        return items
            .map((item) => {
                const bookTitle = item.buku
                    ? item.buku.judul
                    : item.pinjam && item.pinjam.buku
                    ? item.pinjam.buku.judul
                    : "Judul tidak tersedia";

                const userName = item.user
                    ? item.user.name
                    : item.pinjam && item.pinjam.user
                    ? item.pinjam.user.name
                    : "User tidak tersedia";

                return `
                <div class="bg-white p-4 rounded-lg shadow-md mb-4 dark:bg-gray-800">
                    <h4 class="text-lg font-bold text-blue-950 mb-2 dark:text-white">${bookTitle}</h4>
                    <div class="grid grid-cols-2 gap-4">
                        <p class="text-gray-600 dark:text-white">
                            <span class="font-semibold">Peminjam:</span><br>
                            ${userName}
                        </p>
                        <p class="text-gray-600 dark:text-white">
                            <span class="font-semibold">Tanggal Pinjam:</span><br>
                            ${formatDate(
                                item.tanggal_pinjam ||
                                    item.pinjam?.tanggal_pinjam
                            )}
                        </p>
                        <p class="text-gray-600 dark:text-white">
                            <span class="font-semibold">Tanggal Kembali:</span><br>
                            ${formatDate(item.tanggal_kembali)}
                        </p>
                    </div>
                    ${
                        showReturnButton
                            ? `
                        <button
                            data-pinjam-id="${item.id}"
                            class="return-book-btn mt-4 px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition duration-200">
                            Sudah Dikembalikan
                        </button>
                    `
                            : ""
                    }
                    ${
                        item.denda
                            ? `<p class="mt-2 text-red-600 font-semibold">Denda: Rp ${item.denda.toLocaleString(
                                  "id-ID"
                              )}</p>`
                            : ""
                    }
                </div>
            `;
            })
            .join("");
    }

    function handleBookReturn(pinjamId) {
        if (!confirm("Apakah Anda yakin buku ini sudah dikembalikan?")) {
            return;
        }

        fetch(`/admin/mark-returned/${pinjamId}`, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": csrfToken,
                "Content-Type": "application/json",
            },
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.status === "success") {
                    alert(data.message);
                    loadHistory(); // Refresh history
                } else {
                    alert(
                        data.message ||
                            "Terjadi kesalahan saat mengembalikan buku"
                    );
                }
            })
            .catch((error) => {
                console.error("Error:", error);
                alert("Terjadi kesalahan saat mengembalikan buku");
            });
    }

    // Utility function for date formatting
    function formatDate(dateString) {
        if (!dateString) return "-";
        const options = {
            weekday: "long",
            year: "numeric",
            month: "long",
            day: "numeric",
        };
        return new Date(dateString).toLocaleDateString("id-ID", options);
    }

    // waktu
    // Waktu
    const LAT = -6.2; // Latitude Jakarta
    const LNG = 106.816666; // Longitude Jakarta
    let currentTimestamp; // Variabel global untuk menyimpan timestamp awal

    async function fetchTimezoneDBTime() {
        try {
            // Ambil API Key yang disuntikkan dari Blade
            const API_KEY = TIMEZONE_DB_API_KEY;

            const response = await fetch(
                `https://api.timezonedb.com/v2.1/get-time-zone?key=${API_KEY}&format=json&by=position&lat=${LAT}&lng=${LNG}`
            );
            const data = await response.json();

            if (data.status === "OK") {
                // Simpan timestamp awal
                currentTimestamp = data.timestamp;

                // Tampilkan waktu awal
                updateTime();

                // Mulai timer untuk memperbarui waktu setiap detik
                setInterval(updateTime, 1000);
            } else {
                console.error(
                    "Error fetching time from TimeZoneDB:",
                    data.message
                );
                document.getElementById("time").innerText =
                    "Failed to load time.";
            }
        } catch (error) {
            console.error("Error fetching time from TimeZoneDB:", error);
            document.getElementById("time").innerText = "Failed to load time.";
        }
    }

    // Fungsi untuk memperbarui waktu berdasarkan timestamp
    function updateTime() {
        if (currentTimestamp) {
            // Tambahkan 1 detik ke timestamp setiap kali fungsi ini dipanggil
            currentTimestamp++;

            // Konversi timestamp ke waktu lokal
            const currentTime = new Date(currentTimestamp * 1000);
            document.getElementById("time").innerText =
                currentTime.toLocaleString("id-ID", {
                    hour12: false,
                });
        }
    }

    // Panggil fungsi fetch saat halaman dimuat
    fetchTimezoneDBTime();
});
