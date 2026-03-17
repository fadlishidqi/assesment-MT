document.addEventListener('DOMContentLoaded', function () {
    const tbodyKelas = document.getElementById('tbodyKelas');

    // Fungsi untuk Load Data Kelas dari REST API
    function loadKelas() {
        fetch('/api/kelas')
            .then(response => response.json())
            .then(res => {
                tbodyKelas.innerHTML = '';
                if (res.success && res.data.length > 0) {
                    res.data.forEach(k => {
                        const tr = document.createElement('tr');
                        tr.setAttribute('data-id', k.id);
                        tr.innerHTML = `
                            <td>${k.id}</td>
                            <td class="nama-kelas">${k.nama_kelas}</td>
                            <td>
                                <button class="btn btn-warning btn-sm btn-edit" data-id="${k.id}" data-nama="${k.nama_kelas}">Edit</button>
                                <button class="btn btn-danger btn-sm btn-delete" data-id="${k.id}">Hapus</button>
                            </td>
                        `;
                        tbodyKelas.appendChild(tr);
                    });
                } else {
                    tbodyKelas.innerHTML = `<tr><td colspan="3" class="text-center">Data kelas belum ada.</td></tr>`;
                }
            })
            .catch(error => console.error('Error fetching data:', error));
    }

    // Panggil saat halaman pertama kali dibuka
    loadKelas();

    // Fungsi Tambah Kelas via API
    document.getElementById('btnTambahKelas').addEventListener('click', function () {
        Swal.fire({
            title: 'Tambah Kelas',
            input: 'text',
            inputPlaceholder: 'Masukkan nama kelas',
            showCancelButton: true,
            confirmButtonText: 'Simpan',
            cancelButtonText: 'Batal',
            preConfirm: (nama_kelas) => {
                if (!nama_kelas) Swal.showValidationMessage('Nama kelas tidak boleh kosong!');
                return nama_kelas;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Yakin ingin menyimpan?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Simpan!',
                }).then((confirmResult) => {
                    if (confirmResult.isConfirmed) {
                        fetch('/api/kelas', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ nama_kelas: result.value })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if(data.success) {
                                Swal.fire('Berhasil!', data.message, 'success');
                                loadKelas();
                            } else {
                                Swal.fire('Gagal!', data.message, 'error');
                            }
                        });
                    }
                });
            }
        });
    });

    // Delegasi Event untuk Tombol Edit dan Hapus (Karena tombol di-generate dinamis oleh JS)
    tbodyKelas.addEventListener('click', function(e) {
        
        // --- Fungsi Edit Kelas ---
        if (e.target.classList.contains('btn-edit')) {
            const id = e.target.getAttribute('data-id');
            const namaLama = e.target.getAttribute('data-nama');

            Swal.fire({
                title: 'Edit Kelas',
                input: 'text',
                inputValue: namaLama,
                showCancelButton: true,
                confirmButtonText: 'Update',
                preConfirm: (nama_baru) => {
                    if (!nama_baru) Swal.showValidationMessage('Nama tidak boleh kosong!');
                    return nama_baru;
                }
            }).then((result) => {
                if (result.isConfirmed && result.value !== namaLama) {
                    Swal.fire({
                        title: 'Simpan perubahan?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, Update!'
                    }).then((confirmResult) => {
                        if (confirmResult.isConfirmed) {
                            fetch(`/api/kelas/${id}`, {
                                method: 'PUT',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({ nama_kelas: result.value })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if(data.success) {
                                    Swal.fire('Terupdate!', data.message, 'success');
                                    loadKelas(); // Refresh tabel
                                }
                            });
                        }
                    });
                }
            });
        }

        // --- Fungsi Delete Kelas ---
        if (e.target.classList.contains('btn-delete')) {
            const id = e.target.getAttribute('data-id');

            Swal.fire({
                title: 'Apakah kamu yakin?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/api/kelas/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if(data.success) {
                            Swal.fire('Terhapus!', data.message, 'success');
                            loadKelas();
                        }
                    });
                }
            });
        }
    });
});