<div class="wrap">
    <h1>اجازه دسترسی محصول</h1>

    <p>&nbsp;</p>

    <form id="grant_product_access">
        <input type="email" value="" name="email" placeholder="ایمیل مشتری" style="margin-bottom: 10px">
        <br>
        <?php wp_nonce_field('dwgaccess_nonce_grant', 'dwgaccess_nonce'); ?>
        <button type="submit" class="button alt">اعطای دسترسی</button>
    </form>
    <p></p>
    <div id="grant_results"></div>

    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>

    <div class="copyright">ایجاد شده توسط <a href="https://www.dornaweb.com" target="_blank">درناوب</a></div>
</div>
