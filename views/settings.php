<?php
$mail_subject = rawurlencode( "Get my Resoc Site Id for WooCommerce" );
$mail_body = rawurlencode( "Hello,

I am using Resoc Social Image Beautifier for WooCommerce and I would like to brand my product images. Could you send me my Resoc Site Id?

I have attached my site logo to this email so you can use it in the design. In addition, the colors used throughout the site are [#123456] and [#abcdef].

I have noted that you will prepare this for free, without engagement.

Regards
" );
?>
<div class="wrap">
	<?php screen_icon() ?>
	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>

  <p>
    By default, Resoc Social Image Beautifier for WooCommerce crops your
    product images automatically when they are shared on social networks.
  </p>

  <p>
    By providing a <strong>Resoc Site Id</strong>, you can add your logo or name
    to the images and let your customers's friends and followers notice your brand.
    In the future, an editor will allow you to craft your own branding design.
    For the moment, there is no such editor and we handle this task for you,
    for free. Submit your logo and a profesional will prepare your branding design.
  </p>

  <p>
    <a
      class="button-primary"
      href="mailto:contact@resoc.io?subject=<?php echo $mail_subject ?>&body=<?php echo $mail_body ?>"
    >
      Request my design and Resoc Site Id
    </a>
  </p>

	<form action="<?php echo $admin_url ?>" method="post" id="rsibfwc-settings-form">
		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row">Resoc Site Id</th>
					<td>
            <input
              type="text"
              name="<?php echo Resoc_SIBfWC::OPTION_RESOC_SITE_ID ?>"
              value="<?php echo $site_id ?>"
            />
					</td>
				</tr>
			</tbody>
		</table>

    <input type="hidden" name="<?php echo Resoc_SIBfWC::SETTINGS_FORM ?>" value="1">

		<input name="Submit" type="submit" class="button-primary" value="Save changes">
	</form>
</div>
