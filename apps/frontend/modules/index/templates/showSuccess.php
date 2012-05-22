<table>
  <tbody>
    <tr>
      <th>Id:</th>
      <td><?php echo $solar_index->getId() ?></td>
    </tr>
    <tr>
      <th>Text page:</th>
      <td><?php echo $solar_index->getTextPage() ?></td>
    </tr>
    <tr>
      <th>Created at:</th>
      <td><?php echo $solar_index->getCreatedAt() ?></td>
    </tr>
    <tr>
      <th>Updated at:</th>
      <td><?php echo $solar_index->getUpdatedAt() ?></td>
    </tr>
  </tbody>
</table>

<hr />

<a href="<?php echo url_for('index/edit?id='.$solar_index->getId()) ?>">Edit</a>
&nbsp;
<a href="<?php echo url_for('index/index') ?>">List</a>
