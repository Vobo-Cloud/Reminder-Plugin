<div class="col-md-12" style="margin-top: 20px; margin-bottom: 20px;">
    <div class="card">
        <div class="card-header bg-transparent border-bottom">
            Daily Reminder List <?php echo (count($data["reminder"]) > 0) ? '<span class="badge badge-danger no">REMINDER FOUND</span>' : '<span class="badge badge-danger ok">'.date("m.d.Y").'</span>'; ?>
        </div>
        <div class="card-body">
            <blockquote class="card-bodyquote">

                <?php $counter = 0; if(count($data["reminder"]) > 0): ?>

                    <?php foreach ($data["reminder"] as $item): ?>
                        <div style="margin-top: 5px;" class="alert alert-danger mb-0" role="alert"><strong style="color: black;"><?php echo ++$counter; ?> : </strong> <?php echo $item; ?></div>
                    <?php endforeach; ?>

                <?php else: ?>
                    <div style="margin-top: 10px;" class="alert alert-warning" role="alert">Reminder for  <strong><?php echo date("m.d.Y"); ?></strong> was not found.</div>
                <?php endif; ?>
            </blockquote>
        </div>
    </div>

</div>