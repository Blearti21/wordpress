<?php
/*
Template Name: Register
*/
get_header(); ?>

<div class="register-form">
    <h2>Regjistrohu</h2>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['user_register'])) {
        $username = sanitize_user($_POST['username']);
        $email = sanitize_email($_POST['email']);
        $password = $_POST['password'];

        $errors = [];

        if (empty($username) || empty($email) || empty($password)) {
            $errors[] = "Të gjitha fushat janë të detyrueshme.";
        }

        if (username_exists($username)) {
            $errors[] = "Ky emër përdoruesi është i zënë.";
        }

        if (email_exists($email)) {
            $errors[] = "Ky email është regjistruar më parë.";
        }

        if (strlen($password) < 6) {
            $errors[] = "Fjalëkalimi duhet të jetë të paktën 6 karaktere.";
        }

        if (empty($errors)) {
            $user_id = wp_create_user($username, $password, $email);
            if (!is_wp_error($user_id)) {
                echo '<p style="color:green;">Regjistrimi u krye me sukses!</p>';
            } else {
                echo '<p style="color:red;">Gabim gjatë regjistrimit.</p>';
            }
        } else {
            foreach ($errors as $error) {
                echo '<p style="color:red;">' . $error . '</p>';
            }
        }
    }
    ?>

    <form method="post">
        <p>
            <input type="text" name="username" placeholder="Përdoruesi" required>
        </p>
        <p>
            <input type="email" name="email" placeholder="Email" required>
        </p>
        <p>
            <input type="password" name="password" placeholder="Fjalëkalimi" required>
        </p>
        <p>
            <input type="submit" name="user_register" value="Regjistrohu">
        </p>
    </form>
</div>

<?php get_footer(); ?>
