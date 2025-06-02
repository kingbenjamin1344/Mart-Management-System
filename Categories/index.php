<?php
include 'function.php';
// Your PHP code here.

// Home Page template below.
?>

<?=template_header('Home')?>

<div class="content">
    <!-- Your content here -->
</div>

<?=template_footer()?>

<div class="bottom-nav">
    <nav>
        <ul>
            <li><a href="../Mart/test.php">Home</a></li>
            <li><a href="about.php">About</a></li>
            <li><a href="">Services</a></li>
            <li><a href="../Contact/profile.php">Contact</a></li>
        </ul>
    </nav>
    
    <nav>
        <ul>
            <li><a href="../Mart/test.php"></a></li>
            <li><a href="about.php"></a></li>
            <li><a href=""></i></a></li>
            <li><a href="../Contact/profile.php"></a></li>
            <li><a href="../Contact/profile.php"></a></li>
        </ul>
    </nav>

    <nav>
        <ul>
            <li><a href="../Mart/test.php"></a></li>
            <li><a href="about.php"></a></li>
            <li><a href=""><i class="fa fa-copyright" aria-hidden="true"></i> Copy Rights 2024</a></li>
            <li><a href="../Contact/profile.php"></a></li>
            <li><a href="../Contact/profile.php"></a></li>
        </ul>
    </nav>
</div>

<style>
    html, body {
        height: 100%;
        margin: 0;
    }

    body {
        background-image: url('coto.jpg'); /* Add this line */
        background-size: cover; /* Ensures the image covers the whole background */
        background-position: center; /* Centers the image */
        background-repeat: no-repeat; /* Prevents the image from repeating */
    }

    .bottom-nav {
        position: fixed;
        bottom: 0;
        width: 100%;
        background-color: #333;
        padding: 10px 0;
    }

    .bottom-nav nav ul {
        list-style: none;
        margin: 0;
        padding: 0;
        text-align: center;
    }

    .bottom-nav nav ul li {
        display: inline;
        margin: 0 15px;
    }

    .bottom-nav nav ul li a {
        color: #fff;
        text-decoration: none;
        font-size: 16px;
    }

    .bottom-nav nav ul li a:hover {
        text-decoration: underline;
    }
</style>
