@extends('layouts.admin')
@section('styles')

<style>
h3{
    font-weight:700;
}
    .heading{
        font-size:16px;
    }
    select,.input-field {
        background: #FFFFFF;
        box-shadow: 0px 1px 14px rgba(0, 0, 0, 0.25);
        border-radius: 10px;
        padding:10px;
    }
    .rewardSubmit-btn, .edit-btn,.condition-btn {
    background: #1f224f;
    width: 280px;
    height: 40px;
    color: #fff;
    font-size: 14px;
    border: 0px;
    margin-top: 15px;
    -webkit-transition: all 0.3s ease-in;
    -o-transition: all 0.3s ease-in;
    transition: all 0.3s ease-in;
    box-shadow: 0px 1px 14px rgba(0, 0, 0, 0.25);
    border-radius: 10px;
}
.edit-btn {
    width: 120px;
}
.condition-btn {
    width: 220px;
    height: 25px;
}

.condition{
    background:#ffffff;
    margin-top: 20px;
}
.condition .edit-btn {
    width: 60px;
    height: 30px;
}
</style>

@endsection

@section('content')

    <div class="content-area">
        <div class="add-campaign-content">
            <div class="row">
                <div class="col-lg-12">
                    <div class="product-description">
                        <h3>Reward point condition</h3>
                        <div class="body-area">
                        @include('includes.admin.form-error') 
                        <form id="geniusformdata" action="#" method="POST" enctype="multipart/form-data">
                            {{csrf_field()}}

                            <div class="row pt-3">
                                <div class="col-lg-6">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="row">
                                                <div class="col-lg-5">
                                                    <p>Ten Point is equal to :</p> 
                                                </div>
                                                <div class="col-lg-3">
                                                    <input type="number" class="input-field" name="offer">
                                                </div>
                                                <div class="col-lg-4">
                                                    <p>Tk</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>  
                                </div>

                                <div class="col-lg-4">
                                    <button class="edit-btn" type="submit">{{ __('Edit') }}</button>
                                </div>
                            </div>
                            <div class="row pt-3 condition">
                            <div class="col-lg-12">
                            <div class="row">
                                <div class="col-lg-6">
                                    <h3>condition: 1</h3>
                                </div>
                                <div class="col-lg-6">
                                    <button class="edit-btn" type="submit">{{ __('Edit') }}</button>
                                </div>
                            </div>
                                
                            </div>
                                <div class="col-lg-6">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="row">
                                                <div class="col-lg-5">
                                                    <p>One Can redeem point :</p> 
                                                </div>
                                                <div class="col-lg-3">
                                                    <input type="number" class="input-field" placeholder="{{ __('100') }}" name="offer">
                                                </div>
                                                <div class="col-lg-4">
                                                    <p>On One invoice</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    

                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="row">
                                                <div class="col-lg-5">
                                                    <p>Minimum Purchase amount :</p> 
                                                </div>
                                                <div class="col-lg-3">
                                                    <input type="number" class="input-field" placeholder="{{ __('1000') }}" name="offer">
                                                </div>
                                                <div class="col-lg-4">
                                                    <p>Tk</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>   
                                </div>
                            </div>
                            <div class="row">
                                        <div class="col-lg-6 text-right">
                                            <button class="condition-btn" type="submit">{{ __('Add new condition') }}</button>
                                        </div>
                                    </div> 
                            <div class="row pt-4">
                                <div class="col-lg-12 text-left">
                                    <button class="rewardSubmit-btn" type="submit">{{ __('Submit') }}</button>
                                </div>
                            </div>
                        </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    


@endsection
@section('scripts')
<script type="text/javascript">
	

</script>
@endsection