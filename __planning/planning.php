Requirements:
1. HTML/CSS/PHP/MYSQL (JS optional)
2. 4 tables, 3 linkages between them i.e. of ABCD, any of A-B to C-D 
3. user authentication
4. RBAC (read/edit/delete)
5. index routing
6. >=2 data management forms (add/edit/delete), 

Tables:
user details (id/name/email/password/role)
post system (extra col: flair, reported, removed) OR SKU list (extra)
comment section (extra col: reported, removed)
user-saved content (e.g. favourites, shopping cart)

edit comment
> add form in dropdown or equivalent bootstrap
> remember to id the (data-bs-)target appropriately with <?php [X]["id"] ?>


<?php if (isAdmin() || isEditor()): ?>
  <?php else: ?>
          <input 
            type="hidden"
            name="status"
            value="<?= $post["status"]; ?>"
          />
        <?php endif; ?>