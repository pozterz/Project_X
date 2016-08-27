@extends('main.template') 
@section('content')
<div class="container">
    <div class="alert alert-dismissible alert-warning">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <h4>Warning!</h4>
        <p>Best check yo self, you're not looking too good. Nulla vitae elit libero, a pharetra augue. Praesent commodo cursus magna, <a href="#" class="alert-link">vel scelerisque nisl consectetur et</a>.</p>
    </div>
    <div class="alert alert-dismissible alert-danger">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>Oh snap!</strong> <a href="#" class="alert-link">Change a few things up</a> and try submitting again.
    </div>
    <span class="label label-default">Default</span>
    <span class="label label-primary">Primary</span>
    <span class="label label-success">Success</span>
    <span class="label label-warning">Warning</span>
    <span class="label label-danger">Danger</span>
    <span class="label label-info">Info</span>
    <br/>
    <br/>
    <ul class="nav nav-pills">
        <li class="active"><a href="#">Home <span class="badge">42</span></a></li>
        <li><a href="#">Profile <span class="badge"></span></a></li>
        <li><a href="#">Messages <span class="badge">3</span></a></li>
    </ul>
    <br/>
    <ul class="pagination">
        <li class="disabled"><a href="#">&laquo;</a></li>
        <li class="active"><a href="#">1</a></li>
        <li><a href="#">2</a></li>
        <li><a href="#">3</a></li>
        <li><a href="#">4</a></li>
        <li><a href="#">5</a></li>
        <li><a href="#">&raquo;</a></li>
    </ul>
    <ul class="pagination pagination-lg">
        <li class="disabled"><a href="#">&laquo;</a></li>
        <li class="active"><a href="#">1</a></li>
        <li><a href="#">2</a></li>
        <li><a href="#">3</a></li>
        <li><a href="#">&raquo;</a></li>
    </ul>
    <ul class="pagination pagination-sm">
        <li class="disabled"><a href="#">&laquo;</a></li>
        <li class="active"><a href="#">1</a></li>
        <li><a href="#">2</a></li>
        <li><a href="#">3</a></li>
        <li><a href="#">4</a></li>
        <li><a href="#">5</a></li>
        <li><a href="#">&raquo;</a></li>
    </ul>
    <h2>Emphasis classes</h2>
    <p class="text-muted">Fusce dapibus, tellus ac cursus commodo, tortor mauris nibh.</p>
    <p class="text-primary">Nullam id dolor id nibh ultricies vehicula ut id elit.</p>
    <p class="text-warning">Etiam porta sem malesuada magna mollis euismod.</p>
    <p class="text-danger">Donec ullamcorper nulla non metus auctor fringilla.</p>
    <p class="text-success">Duis mollis, est non commodo luctus, nisi erat porttitor ligula.</p>
    <p class="text-info">Maecenas sed diam eget risus varius blandit sit amet non magna.</p>
    <h1>Heading 1</h1>
    <h2>Heading 2</h2>
    <h3>Heading 3</h3>
    <h4>Heading 4</h4>
    <h5>Heading 5</h5>
    <h6>Heading 6</h6>
    <p class="lead">Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor.</p>
    <blockquote class="blockquote-reverse">
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.</p>
        <small>Someone famous in <cite title="Source Title">Source Title</cite></small>
    </blockquote>
    <blockquote>
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.</p>
        <small>Someone famous in <cite title="Source Title">Source Title</cite></small>
    </blockquote>
    <form class="form-horizontal">
        <fieldset>
            <legend>Legend</legend>
            <div class="form-group">
                <label for="inputEmail" class="col-lg-2 control-label">Email</label>
                <div class="col-lg-10">
                    <input type="text" class="form-control" id="inputEmail" placeholder="Email">
                </div>
            </div>
            <div class="form-group">
                <label for="inputPassword" class="col-lg-2 control-label">Password</label>
                <div class="col-lg-10">
                    <input type="password" class="form-control" id="inputPassword" placeholder="Password">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox"> Checkbox
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="textArea" class="col-lg-2 control-label">Textarea</label>
                <div class="col-lg-10">
                    <textarea class="form-control" rows="3" id="textArea"></textarea>
                    <span class="help-block">A longer block of help text that breaks onto a new line and may extend beyond one line.</span>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-2 control-label">Radios</label>
                <div class="col-lg-10">
                    <div class="radio">
                        <label>
                            <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked=""> Option one is this
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                            <input type="radio" name="optionsRadios" id="optionsRadios2" value="option2"> Option two can be something else
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="select" class="col-lg-2 control-label">Selects</label>
                <div class="col-lg-10">
                    <select class="form-control" id="select">
                        <option>1</option>
                        <option>2</option>
                        <option>3</option>
                        <option>4</option>
                        <option>5</option>
                    </select>
                    <br>
                    <select multiple="" class="form-control">
                        <option>1</option>
                        <option>2</option>
                        <option>3</option>
                        <option>4</option>
                        <option>5</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <div class="col-lg-10 col-lg-offset-2">
                    <button type="reset" class="btn btn-default">Cancel</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </fieldset>
    </form>
</div>
@endsection
