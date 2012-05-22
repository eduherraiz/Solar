<h1>Solar pages List</h1>

<table>
  <thead>
    <tr>
      <th>Id</th>
      <th>Title</th>
      <th>Password</th>
      <th>Text page</th>
      <th>Created at</th>
      <th>Updated at</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($solar_pages as $solar_page): ?>
    <tr>
      <td><a href="<?php echo url_for('page/show?id='.$solar_page->getId()) ?>"><?php echo $solar_page->getId() ?></a></td>
      <td><?php echo $solar_page->getTitle() ?></td>
      <td><?php echo $solar_page->getPassword() ?></td>
      <td><?php echo $solar_page->getTextPage() ?></td>
      <td><?php echo $solar_page->getCreatedAt() ?></td>
      <td><?php echo $solar_page->getUpdatedAt() ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

  <a href="<?php echo url_for('page/new') ?>">New</a>
