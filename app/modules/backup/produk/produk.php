<?php
/**
* PHP *	Year		: 2022
* Author	: Agus Prawoto Hadi
* Website	: https://pdsionline.org
* Year		: 2021-2022
*/

$koneksi 	= mysqli_connect($database['host'], $database['username'], $database['password'], $database['database']);

switch ($_GET['action']) 
{
    default: 
        action_notfound();
    
    	// INDEX 
    case 'index':
        $message = [];
        if (!empty($_POST['delete'])) {
			
			cek_hakakses('delete_data');
            $delete = $db->delete('produk', ['id_produk' => $_POST['id']]);
			
            if ($delete) {
                $message['status'] = 'ok';
                $message['message'] = 'Data berhasil dihapus';
            } else {
                $message['status'] = 'error';
                $message['message'] = 'Data gagal dihapus';
            }
        }
		
        $sql = 'SELECT * FROM produk' . where_own();
        $query 		= mysqli_query($koneksi, $sql);
        $result 	= mysqli_fetch_all($query, MYSQLI_ASSOC);
        $data['result'] = $result;

        if (!$data['result']) {
			$message['status'] = 'error';
			$message['message'] = 'Data tidak ditemukan';
		}
		
		$data['message'] = $message;

        load_view('views/result.php', $data);
    
    case 'add':

        $result['message']['status'] = '';
        $result['message']['message'] = '';
		$result['id_produk'] = '';
		
		$data['message'] = '';
        if (!empty($_POST['submit'])) {
            $result = save_data();
			$data['message'] = $result['message'];
        }

        $data['title'] = !empty($_POST['id']) || @$result['message']['status'] == 'ok' ? 'Edit Data Produk' : 'Tambah Data Produk';
        $data['id_produk'] = $result['id_produk'];

        load_view('views/form.php', $data);

    case 'edit':
		
		cek_hakakses('update_data');
		
        if (empty($_GET['id']))
            data_notfound();

        $result['message'] = [];
        if (!empty($_POST['submit'])) {
            $result = save_data();
        }

        $sql = 'SELECT * FROM produk WHERE id_produk = ?';
        $produk = $db->query($sql, $_GET['id'])->getRowArray();

        if (!$produk)
            data_notfound();

        $data['title'] = 'Edit Data Produk';
        $data['produk'] = $produk;
        $data['message'] = $result['message'];
        $data['id_produk'] = $_GET['id'];
        load_view('views/form.php', $data);
}

function validate_form() {
    $error = false;
    if (empty(trim($_POST['nama_produk']))) {
        $error[] = 'Nama Produk harus diisi';
    }

    if (empty(trim($_POST['deskripsi_produk']))) {
        $error[] = 'Deskripsi Produk harus diisi';
    }

    return $error;
}

function save_data() 
{
    global $db;
    $message = [];
    $id_produk = '';
    if (!empty($_POST['submit'])) {
        $error = validate_form();
        if ($error) {
            $message['status'] = 'error';
            $message['message'] = $error;
        } else {
            $data_db['nama_produk'] = $_POST['nama_produk'];
            $data_db['deskripsi_produk'] = $_POST['deskripsi_produk'];
            if (!empty($_POST['id'])) {
				$data_db['id_user_edit'] = $_SESSION['user']['id_user'];
                $query = $db->update('produk', $data_db, ['id_produk' => $_POST['id']]);
                $id_produk = $_POST['id'];
            } else {
				$data_db['id_user_input'] = $_SESSION['user']['id_user'];
                $query = $db->insert('produk', $data_db);
                $id_produk = $db->lastInsertId();
            }
            
            if ($query) {
                $message['status'] = 'ok';
                $message['message'] = 'Data berhasil disimpan';
            } else {
                $message['status'] = 'error';
                $message['message'] = 'Data gagal disimpan';
            }
        }
    }
    return ['message' => $message, 'id_produk' => $id_produk];
}