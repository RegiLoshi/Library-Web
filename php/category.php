<?php
    include 'header.php';
?>

<div class="card mb-4">
    <div class="card-header">
        <div class="row">
            <div class="col col-md-6">
                <i class="fas fa-table me-1"></i> Category Management
            </div>
            <div class="col col-md-6" align="right">
                <a href="category.php?action=add" class="btn btn-success btn-sm">Add</a>
            </div>
        </div>
    </div>
    <div class="card-body">

        <table id="datatablesSimple">
            <!-- to be edited -->
        </table>
    </div>                
</div>

<?php
    include 'footer.php';
?>