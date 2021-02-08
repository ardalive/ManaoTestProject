<form class="logout" action="session_end.php" method="POST">
    <h1>Hello, <?php echo $_SESSION['name'];?>!</h1>
    <button type="submit" class="btn btn-primary">Logout</button>
</form>