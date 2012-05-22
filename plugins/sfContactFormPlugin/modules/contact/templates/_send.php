
<h2>You received a new messega from <?php echo $sf_request->getUri() ?></h1>

<ul>
  <li>Name: <?php echo $form->getValue('name') ?></li>
  <li>Email: <?php echo $form->getValue('email') ?></li>
  <li>Message: <?php echo nl2br($form->getValue('message')) ?></li>

</ul>
