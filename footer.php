<?php
	if(isset($_SESSION["munkamenet"]) AND $_SESSION["munkamenet"]!="")
	{
?>
</div>

<footer class="footer">
    <div class="footer-inner-wraper">
        <div class="d-sm-flex justify-content-center justify-content-sm-between">
            <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright &copy; 2024-<?php echo date("Y"); ?> <a href="https://trsgroup.hu/" target="_blank">TrS Group</a>. All rights reserved.</span>
            <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Hand-crafted &amp; made with <i class="mdi mdi-heart text-danger"></i></span>
        </div>
    </div>
</footer>

<div class="notification col-11 col-md-8 col-lg-4 py-2 px-4 justify-content-between d-none" id="notification">
    <span id="notification_content" class="px-2 py-1 d-inline-block"></span>
    <span id="notification_close" class="px-2 py-1 d-inline-block">bezárás</span>
</div>

<?php
if(isset($_SESSION["php_notification"])){
    echo '<input type="hidden" id="php_notification" value="' . $_SESSION["php_notification"] . '" />';
    unset($_SESSION["php_notification"]);
}
elseif(isset($_SESSION["php_err_notification"])){
    echo '<input type="hidden" id="php_err_notification" value="' . $_SESSION["php_err_notification"] . '" />';
    unset($_SESSION["php_err_notification"]);
}
?>

</div>
</div>
</div>

<!--vendor js -->
<script src="./assets/js/vendor.bundle.base.js"></script>
<script src="./assets/vendors/chart.js/Chart.min.js"></script>
<script src="./assets/js/off-canvas.js"></script>
<script src="./assets/js/hoverable-collapse.js"></script>
<script src="./assets/js/misc.js"></script>
<script src="./assets/js/dashboard.js"></script>
<!--saját js-ek-->
<script src="./assets/kekcms/js/admin_script.js?v=<?php echo time(); ?>"></script>

</body>
</html>
<?php
	}
?>