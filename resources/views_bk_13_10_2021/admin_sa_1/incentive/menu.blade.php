
<style>
    @media only screen 
    and (min-device-width: 320px) 
    and (max-device-width: 568px)
    and (-webkit-min-device-pixel-ratio: 2) {
        .bgc-white .btn {
            padding: 1rem 1rem !important;
            font-size: 2rem !important;
            width: 300px;
            height: 80px;
        }
    }
    @media only screen 
    and (min-device-width: 375px) 
    and (max-device-width: 812px) 
    and (-webkit-min-device-pixel-ratio: 3) { 
        .bgc-white .btn {
            padding: 1rem 1rem !important;
            font-size: 2rem !important;
            width: 300px;
            height: 80px;
        }
    }
</style>
<div class="row">
    <div class="masonry-item col-md-6 masonry-col mY-10">
        <div class="bgc-white p-20 bd">
            <div style="border-bottom: 1px solid black;">
                <h4 class="c-grey-900 text-center">Brand Promoter</h4>
            </div>
            <div class="mT-10">
                <div class="gap-10 peers" style="justify-content: center;">
                    <div class="peer">
                        <a href="{{ url('incentive.addForm') }}/{{ 1 }}">   
                            <button type="button" class="btn cur-p btn-primary">
                                Add Incentive
                            </button>
                        </a>
                    </div>
                    <div class="peer">
                        <a href="{{ url('incentive.list') }}/{{ 1 }}">
                            <button type="submit" class="btn cur-p btn-secondary">
                                Incentive List
                            </button>
                        </a>
                    </div>
                    <div class="peer">
                        <a href="{{ url('award.addForm') }}/{{ 1 }}">   
                            <button type="button" class="btn cur-p btn-success">
                                Add Special Award
                            </button>
                        </a>
                    </div>
                    <div class="peer">
                        <a href="{{ url('award.list') }}/{{ 1 }}">
                            <button type="submit" class="btn cur-p btn-secondary">
                                Special Award List
                            </button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="masonry-item col-md-6 masonry-col mY-10">
        <div class="bgc-white p-20 bd">
            <div style="border-bottom: 1px solid black;">                
                <h4 class="c-grey-900 text-center">Retailer</h4>
            </div>
            <div class="mT-10">
                <div class="w-100 gap-10 peers" style="justify-content: center;">
                    <div class="peer">
                        <a href="{{ url('incentive.addForm') }}/{{ 2 }}">   
                            <button type="button" class="btn cur-p btn-warning">
                                Add Incentive
                            </button>
                        </a>
                    </div>
                    <div class="peer">
                        <a href="{{ url('incentive.list') }}/{{2}}">
                            <button type="submit" class="btn cur-p btn-secondary">
                                Incentive List
                            </button>
                        </a>
                    </div>
                    <div class="peer">
                        <a href="{{ url('award.addForm') }}/{{ 2 }}">   
                            <button type="button" class="btn cur-p btn-primary">
                                Add Special Award
                            </button>
                        </a>
                    </div>
                    <div class="peer">
                        <a href="{{ url('award.list') }}/{{2}}">
                            <button type="submit" class="btn cur-p btn-secondary">
                                Special Award List
                            </button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

