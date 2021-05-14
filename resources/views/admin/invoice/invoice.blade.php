<div class="row">
    <!-- ./col -->
    <div class="col-lg-2 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-red">
            <div class="inner">
                <h3>{{ $not_signed }}</h3>

                <p>待签收</p>
            </div>
            <div class="icon">
                <i class="fa fa-random"></i>
            </div>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-2 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-green">
            <div class="inner">
                <h3>{{ $signed }}</h3>

                <p>已签收</p>
            </div>
            <div class="icon">
                <i class="fa fa-thumbs-o-up"></i>
            </div>
        </div>
    </div>

</div>
