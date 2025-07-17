<?php
session_start();
require_once './auth/auth.php';
require_once './config/database.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$active_page = 'tabel_users';
$pdo = (new Database())->getConnection();

$stmt = $pdo->query("
    SELECT 
        users.id AS user_id, users.name, users.email, users.phone, users.role, users.is_verified,
        addresses.address,
        identity_verifications.nik, identity_verifications.identity_type, 
        identity_verifications.identity_file, identity_verifications.status AS id_status
    FROM users
    LEFT JOIN addresses ON users.id = addresses.user_id
    LEFT JOIN identity_verifications ON users.id = identity_verifications.user_id
");

$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Pengguna</title>
    <link rel="stylesheet" href="assets/css/sidebarStyle.css">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }

        .main-container {
            margin-left: 230px;
            padding: 20px;
        }

        .title {
            font-size: 28px;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }

        th, td {
            padding: 14px 16px;
            text-align: left;
            border-bottom: 1px solid #eaeaea;
        }

        th {
            background-color: #f1f1f1;
        }

        tr:hover {
            background-color: #f9f9f9;
        }

        .btn {
            padding: 6px 12px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
        }

        .btn-edit {
            background-color: #007bff;
            color: white;
        }

        .btn-delete {
            background-color: #dc3545;
            color: white;
        }

        .btn-profile {
            background-color: #28a745;
            color: white;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 9999;
            padding-top: 80px;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow-y: auto;
            background-color: rgba(0,0,0,0.5);
        }

        .modal-content {
            background-color: #fff;
            margin: auto;
            padding: 25px;
            border-radius: 10px;
            width: 600px;
            max-width: 90%;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }

        .modal-header {
            font-size: 20px;
            margin-bottom: 20px;
        }

        .close {
            float: right;
            font-size: 28px;
            font-weight: bold;
            color: #aaa;
            cursor: pointer;
        }

        .close:hover {
            color: #000;
        }

        .profile-item {
            margin-bottom: 10px;
        }

        .input-full {
            width: 100%;
            padding: 8px;
            margin-top: 4px;
            box-sizing: border-box;
            border-radius: 6px;
            border: 1px solid #ccc;
        }
    </style>
</head>
<body>
    <?php include 'views/components/sidebar.php'; ?>

    <div class="main-container">
        <h2 class="title">Manajemen Pengguna</h2>
        <table>
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Alamat</th>
                    <th>Status Identitas</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= htmlspecialchars($user['name']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td><?= htmlspecialchars($user['phone']) ?></td>
                    <td><?= htmlspecialchars($user['address']) ?></td>
                    <td><?= htmlspecialchars($user['id_status'] ?? 'Belum Ada') ?></td>
                    <td>
                        <button class="btn btn-profile" onclick='openProfileModal(<?= json_encode($user) ?>)'>Lihat</button>
                        <button class="btn btn-edit" onclick='openEditModal(<?= json_encode($user) ?>)'>Edit</button>
                        <button class="btn btn-delete" onclick="deleteUser(<?= $user['user_id'] ?>)">Hapus</button>
                    </td>
                </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>

    <!-- Modal Profil -->
    <div id="profileModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('profileModal')">&times;</span>
            <div class="modal-header">Profil Pengguna</div>
            <div id="profileContent"></div>
        </div>
    </div>

    <!-- Modal Edit -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('editModal')">&times;</span>
            <div class="modal-header">Edit Pengguna</div>
            <form id="editForm">
                <input type="hidden" name="user_id" id="editUserId">
                <div class="profile-item">
                    <label>Nama:</label>
                    <input type="text" name="name" id="editName" class="input-full" required>
                </div>
                <div class="profile-item">
                    <label>Email:</label>
                    <input type="email" name="email" id="editEmail" class="input-full" required>
                </div>
                <div class="profile-item">
                    <label>Telepon:</label>
                    <input type="text" name="phone" id="editPhone" class="input-full">
                </div>
                <div class="profile-item">
                    <label>Alamat:</label>
                    <textarea name="address" id="editAddress" class="input-full" rows="3"></textarea>
                </div>
                <div style="text-align: right; margin-top: 15px;">
                    <button type="button" class="btn btn-delete" onclick="closeModal('editModal')">Batal</button>
                    <button type="submit" class="btn btn-edit">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openProfileModal(user) {
            const content = `
                <div class="profile-item"><strong>Nama:</strong> ${user.name}</div>
                <div class="profile-item"><strong>Email:</strong> ${user.email}</div>
                <div class="profile-item"><strong>Phone:</strong> ${user.phone || '-'}</div>
                <div class="profile-item"><strong>Alamat:</strong> ${user.address || '-'}</div>
                <div class="profile-item"><strong>NIK:</strong> ${user.nik || '-'}</div>
                <div class="profile-item"><strong>Jenis Identitas:</strong> ${user.identity_type || '-'}</div>
                <div class="profile-item"><strong>Status Verifikasi:</strong> ${user.id_status || '-'}</div>
                ${user.identity_file ? `<div class="profile-item"><strong>File Identitas:</strong><br><img src="uploads/identity/${user.identity_file}" alt="ID" width="300"></div>` : ''}
            `;
            document.getElementById('profileContent').innerHTML = content;
            document.getElementById('profileModal').style.display = 'block';
        }

        function openEditModal(user) {
            document.getElementById('editUserId').value = user.user_id;
            document.getElementById('editName').value = user.name;
            document.getElementById('editEmail').value = user.email;
            document.getElementById('editPhone').value = user.phone || '';
            document.getElementById('editAddress').value = user.address || '';
            document.getElementById('editModal').style.display = 'block';
        }

        function closeModal(id) {
            document.getElementById(id).style.display = 'none';
        }

        window.onclick = function(event) {
            const modals = ['profileModal', 'editModal'];
            modals.forEach(modalId => {
                const modal = document.getElementById(modalId);
                if (event.target === modal) modal.style.display = 'none';
            });
        };

        // Simulasi kirim data edit
        document.getElementById('editForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const data = Object.fromEntries(formData.entries());

            console.log("Data dikirim ke backend:", data);
            alert("Data berhasil disimpan untuk user ID " + data.user_id + " (simulasi)");

            closeModal('editModal');
        });

        function deleteUser(userId) {
            if (confirm("Yakin ingin menghapus user ini?")) {
                alert("User dengan ID " + userId + " dihapus (simulasi).");
            }
        }
    </script>
</body>
</html>
