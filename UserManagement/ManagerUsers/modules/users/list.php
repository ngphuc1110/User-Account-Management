<?php

if(!defined('_LEGIT'))
{
    die('Access Denied');
}

$data = [
    'pageTitle' => 'User Lists'
];

layouts('header2', $data);

if(!isLogin()){
    redirection('?module=auth&action=login');
}

$listUser = getRaw("SELECT * FROM users ORDER BY updateAt");
        // echo '<pre>';
        // print_r($listUser);
        // echo '</pre>';

$smg = getFlashData('smg');
$smg_type = getFlashData('smg_type');
?>

<div class = "container">
    <hr>
    <h2> Users Management </h2>
    <p>
        <a href="?module=users&action=add" class="btn btn-success btn-sm"><i class="fa-solid fa-address-book"> </i> Add User </a>
    </p>
    <table class="table table-bordered">
        <thead>
            <th>ID</th>
            <th>Full Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Status</th>
            <th width ="5%">Edit</th>
            <th width ="5%">Remove</th>
        </thead>
        <tbody>
            <?php
                if(!empty($listUser)):
                    $count = 0;
                    foreach($listUser as $item):
                        $count++;
            ?>
            <tr>
            <td><?php echo $count; ?></td>
            <td><?php echo $item['fullname']; ?></td>
            <td><?php echo $item['email']; ?></td>
            <td><?php echo $item['phone']; ?></td>
            <td><?php echo $item['status'] == 1 ? '<button class="btn btn-success btn-sm">Actived</button>': '<button class="btn btn-danger btn-sm">Inactivated</button>' ?></td>
            <td><a href="?module=users&action=edit&id=<?php echo $item['id']?>" class="btn btn-warning btn-sm"><i class="fa-solid fa-pen-to-square"></a></i></td>
            <td><a href="?module=users&action=delete&id=<?php echo $item['id']?>" style="margin: center;" onclick="return confirm('Are you sure you want to remove ?')" class="btn btn-danger btn-sm"><i class="fa-solid fa-eraser"></i></a></td>
            </tr>
            <?php
                    endforeach;
                else:
            ?>
            <tr>
                <td colspan="7">
                    <div class="alert alert-danger text-center">No User Available !</div>
                </td>
            </tr>
            <?php
                endif;
            ?>
        </tbody>
    </table>
</div>

<?php
layouts('footer2');
?>
