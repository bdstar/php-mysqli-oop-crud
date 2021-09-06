<?php require "header.php"; ?>

<div class="jumbotron text-center" style="margin-bottom:0">
  <h1>Tags</h1>
  <p>Show all Tags</p> 
</div>

<div class="container" style="margin-top:30px">
  <div class="row">
    <div class="col-sm-12">
        <a href="save-tag.php" class="btn btn-info pull-right"><i class="fa fa-plus" aria-hidden="true"></i> Add Tag</a>
        <table id="word-list" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
            <thead>
                <tr>
                    <th>SN</th>
                    <th>Tags</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $tags = $obj->fetch("tag", array(), array());

                $sn = 1;
                foreach($tags as $tag){
                    echo '
                        <tr>
                            <td>'.$sn++.'</td>
                            <td>'.$tag['name'].'</td>
                            <td>'.$tag['updated_at'].'</td>
                            <td class="pull-right">
                                <a href="save-tag.php?action=update&id='.$tag['id'].'" class="btn btn-info"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                                <a href="save-tag.php?action=view&id='.$tag['id'].'" class="btn btn-warning"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                <a id="delete-tag" data-id="'.$tag['id'].'" onclick="deleteData('.$tag['id'].')" class="btn btn-danger"><i class="fa fa-times" aria-hidden="true"></i></a>
                            </td>
                        </tr>
                    ';
                }
                ?>
            </tbody>
        </table>
    </div>

  </div>
</div>

<?php require "footer.php"; ?>


<!--<form id="delete-form-'.$tag['id'].'" action="database.php?page=tag&action=delete&id='.$tag['id'].'" method="POST"></form>-->
<!-- https://sweetalert2.github.io/ -->

