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
        document.body.classList.toggle("dark:bg-gray-800");
    });

    // Navigation Management
    const sections = {
        home: document.getElementById("homeContent"),
        search: document.getElementById("searchContent"),
        history: document.getElementById("historyContent"),
    };

    const menu = {
        home: document.getElementById("menuHome"),
        search: document.getElementById("menuSearch"),
        history: document.getElementById("menuHistory"),
    };

    function showContent(sectionName) {
        Object.values(sections).forEach((section) => {
            section.classList.add("hidden");
        });
        sections[sectionName].classList.remove("hidden");
    }

    Object.keys(menu).forEach((menuName) => {
        menu[menuName].addEventListener("click", (e) => {
            e.preventDefault();
            showContent(menuName);
        });
    });

    // Search Functionality
    const searchInput = document.getElementById("searchInput");
    const searchButton = document.getElementById("searchButton");
    const searchResults = document.getElementById("searchResults");

    function performSearch() {
        const query = searchInput.value.trim();
        if (!query) {
            searchResults.innerHTML =
                '<p class="text-gray-500">Silakan masukkan kata kunci pencarian</p>';
            return;
        }

        searchResults.innerHTML =
            '<p class="text-gray-500">Mencari buku...</p>';

        fetch(`/search?query=${encodeURIComponent(query)}`)
            .then((response) => {
                if (!response.ok) {
                    throw new Error("Network response was not ok");
                }
                return response.json();
            })
            .then((books) => {
                if (books.length === 0) {
                    searchResults.innerHTML =
                        '<p class="text-gray-500">Tidak ada buku yang ditemukan</p>';
                    return;
                }

                searchResults.innerHTML = books
                    .map(
                        (book) => `
                        <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
                            <h3 class="text-xl font-bold text-orange-950 mb-2">${
                                book.judul
                            }</h3>
                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div>
                                    <p class="text-gray-600"><span class="font-semibold">Penulis:</span> ${
                                        book.penulis
                                    }</p>
                                    <p class="text-gray-600"><span class="font-semibold">Penerbit:</span> ${
                                        book.penerbit
                                    }</p>
                                    <p class="text-gray-600"><span class="font-semibold">Tahun:</span> ${
                                        book.tahun
                                    }</p>
                                </div>
                                <div>
                                    <p class="text-gray-600"><span class="font-semibold">ISBN:</span> ${
                                        book.isbn
                                    }</p>
                                    <p class="text-gray-600"><span class="font-semibold">Kategori:</span> ${
                                        book.kategori
                                            ? book.kategori.nama
                                            : "Tidak ada kategori"
                                    }</p>
                                    <p class="text-gray-600">
                                        <span class="font-semibold">Status:</span> 
                                        ${
                                            book.jumlah > 0
                                                ? `<span class="text-green-600">Tersedia (${book.jumlah} buku)</span>`
                                                : '<span class="text-red-600">Tidak Tersedia</span>'
                                        }
                                    </p>
                                </div>
                            </div>
                            ${
                                book.jumlah > 0
                                    ? `<button onclick="borrowBook(${book.id})" 
                                    class="w-full mt-2 px-4 py-2 bg-orange-950 text-white rounded-lg hover:bg-orange-800 transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-orange-500">
                                    Pinjam Buku
                                </button>`
                                    : '<button disabled class="w-full mt-2 px-4 py-2 bg-gray-400 text-white rounded-lg cursor-not-allowed">Tidak Tersedia</button>'
                            }
                        </div>
                    `
                    )
                    .join("");
            })
            .catch((error) => {
                console.error("Error:", error);
                searchResults.innerHTML =
                    '<p class="text-red-500">Terjadi kesalahan saat mencari buku</p>';
            });
    }

    searchButton.addEventListener("click", performSearch);
    searchInput.addEventListener("keypress", function (e) {
        if (e.key === "Enter") {
            performSearch();
        }
    });

    // Borrow Book Function
    window.borrowBook = function (bookId) {
        if (!confirm("Apakah Anda yakin ingin meminjam buku ini?")) {
            return;
        }

        const token = document
            .querySelector('meta[name="csrf-token"]')
            .getAttribute("content");

        fetch(`/borrow/${bookId}`, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": token,
                "Content-Type": "application/json",
            },
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.status === "success") {
                    alert(data.message);
                    performSearch(); // Refresh search results
                } else {
                    alert(
                        data.message || "Terjadi kesalahan saat meminjam buku"
                    );
                }
            })
            .catch((error) => {
                console.error("Error:", error);
                alert("Terjadi kesalahan saat meminjam buku");
            });
    };

    // History Loading
    function loadHistory() {
        const historyResults = document.getElementById("historyResults");
        historyResults.innerHTML =
            '<p class="text-gray-500">Memuat riwayat peminjaman...</p>';

        fetch("/history")
            .then((response) => {
                if (!response.ok) {
                    throw new Error("Network response was not ok");
                }
                return response.json();
            })
            .then((data) => {
                historyResults.innerHTML = `
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-xl font-semibold mb-4 text-orange-950">Buku yang Sedang Dipinjam</h3>
                            ${displayHistoryItems(data.borrowed)}
                        </div>
                        
                        <div>
                            <h3 class="text-xl font-semibold mb-4 text-orange-950">Riwayat Pengembalian</h3>
                            ${displayHistoryItems(data.returned)}
                        </div>
                    </div>
                `;
            })
            .catch((error) => {
                console.error("Error:", error);
                historyResults.innerHTML =
                    '<p class="text-red-500">Terjadi kesalahan saat memuat riwayat peminjaman</p>';
            });
    }

    function displayHistoryItems(items) {
        if (!items || items.length === 0) {
            return '<p class="text-gray-500">Tidak ada data</p>';
        }

        return items
            .map(
                (item) => `
                <div class="bg-white p-4 rounded-lg shadow-md mb-4 hover:shadow-lg transition-shadow duration-300">
                    <h4 class="text-lg font-bold text-orange-950 mb-2">${
                        item.buku?.judul || "Judul tidak tersedia"
                    }</h4>
                    <div class="grid grid-cols-2 gap-4">
                        <p class="text-gray-600">
                            <span class="font-semibold">Tanggal Pinjam:</span><br>
                            ${formatDate(item.tanggal_pinjam)}
                        </p>
                        <p class="text-gray-600">
                            <span class="font-semibold">Tanggal Kembali:</span><br>
                            ${formatDate(item.tanggal_kembali)}
                        </p>
                    </div>
                    ${
                        item.denda
                            ? `<p class="mt-2 text-red-600 font-semibold">Denda: Rp ${item.denda.toLocaleString(
                                  "id-ID"
                              )}</p>`
                            : ""
                    }
                </div>
            `
            )
            .join("");
    }

    function formatDate(dateString) {
        const options = {
            weekday: "long",
            year: "numeric",
            month: "long",
            day: "numeric",
        };
        return new Date(dateString).toLocaleDateString("id-ID", options);
    }

    // Add event listener for history menu
    document
        .getElementById("menuHistory")
        .addEventListener("click", loadHistory);
});