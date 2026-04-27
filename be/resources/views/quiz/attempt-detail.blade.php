@include("header")

<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">


        <!-- On route vehicles Table -->
        <div class="col-12 order-5">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div class="card-title mb-0">
                        <h5 class="m-0 me-2">Detail Quiz</h5>
                    </div>
                </div>
                <div class="card-datatable border-top">
                    <div class="row">
                        <div class="col-md-8">

                            <table>
                                <tbody>
                                    <tr style="padding:10px">
                                        <td> {{$question_quiz_attempt->question->question}}</td>

                                    </tr>
                                    @foreach($question_quiz_attempt->question->options as $opt)
                                    <tr>
                                        <td><a class="btn btn-sm btn-warning">{{$opt->option}}</a></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-4">
                            @foreach($quiz_attempt->questions as $key => $q)
                            <a class="btn btn-sm btn-primary mb-2" href="{{route('quiz.attempt-detail-question',[$quiz_attempt->uuid,$q->uuid])}}">{{ str_pad($key+1, 2, '0', STR_PAD_LEFT) }}</a>
                            @endforeach
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!--/ On route vehicles Table -->
    </div>
</div>
<!-- / Content -->

@include("footer");