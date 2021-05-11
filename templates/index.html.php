 <?php if (!empty($view['bookmarks'])): ?>
 <table>
     <thead>
     <tr>
         <th>Name</th>
         <th>URL</th>
     </tr>
     </thead>
     <tbody>
     <?php foreach ($view['bookmarks'] as $key => $bookmark): ?>
         <tr>
             <td>
                 view
                 <a href="view.php?id=<?php echo $key; ?>" title="<?php echo $bookmark['title'] ?? ' '; ?>">
                     <?php echo $bookmark['title'] ?? ''; ?>
                 </a>
             </td>
             <td><?php echo $bookmark['url'] ?? ' '; ?></td>
         </tr><?php endforeach; ?>
     </tbody>
 </table>
 <?php else: ?>
     <p>
         List is empty!
     </p>
 <?php endif; ?>
