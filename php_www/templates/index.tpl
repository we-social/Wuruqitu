<!DOCTYPE html>
<html lang="zh-cn">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="keywords" content="误入岐图 wuruqitu">
    <meta name="author" content="h5-Lium">
    <title>{{$PRJ_NAME_CN}}</title>
    <link rel="shortcut icon" href="favicon.ico">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="ico/apple-touch-icon-57-precomposed.png">

    <!-- HTML5 shim -->
    <!--[if lt IE 9]>
      <script src="plugin/html5shim.js"></script>
    <![endif]-->
    <link rel="stylesheet" href="plugin/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="plugin/blackbirdjs/blackbird.css">
    <link rel="stylesheet" href="min/?g=css&02150216">

    <!--[if lte IE 6]>
      <link rel="stylesheet" href="plugin/bootstrap/css/ie6.min.css">
    <![endif]-->
    <!--[if lte IE 7]>
      <link rel="stylesheet" href="plugin/bootstrap/css/ie.css">
    <![endif]-->
  </head>
  <body>
    <div id="my_smarty_vars">
      <input type="hidden" name="_ACTIVE" value="{{$order}}">
      <input type="hidden" name="_COLS" value="{{count($cols)}}">
      <input type="hidden" name="_DIR_UP" value="{{$dir_up}}">
      <input type="hidden" name="_DIR_UP_THUMB" value="{{$dir_up_thumb}}">
    </div>

    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <div class="brand">
            <p>
              <img src="ico/logo-30.png">
              <span>{{$PRJ_NAME_CN}}</span>
            </p>
          </div>
          <div class="nav-collapse collapse">
            <ul class="nav">
              <li id="my_nav_late"><a href="{{query('_o=late&_p=1')}}">时间最晚</a></li>
              <li id="my_nav_comms"><a href="{{query('_o=comms&_p=1')}}">评论最多</a></li>
              <li id="my_nav_goods"><a href="{{query('_o=goods&_p=1')}}">被赞最多</a></li>
            </ul>

            <ul class="nav pull-right">
              <li>
                {{if $mobi}}<a href="?_m=0">体验PC版</a>{{else}}<a href="?_m=1">体验移动版</a>{{/if}}
              </li>
            </ul>
          </div> <!-- /.nav-collapse -->
        </div>
      </div>
    </div>

    <div class="container">
      <div class="hero-unit">
        <img src="img/zhuai.png">
        <h1>今天，你发图了吗？</h1>
        <div class="my-header">
          <p><span>有时一张图片需要另一种视角。</span></p>
          <div class="btn-toolbar">
            <a class="btn btn-large btn-primary" id="my_entry_upload" href="javascript:openUpload()">来一发  &raquo;</a>
          </div>
        </div>
      </div>

      <div class="alert alert-info" id="my_pane_upload">
        <a class="close" href="javascript:closeUpload()">&times;</a>
        <form method="POST" action="up.php" enctype="multipart/form-data" id="my_form_upload">
          <fieldset>
            <label for="my_ipt_pic">图片:</label>
            <input type="file" name="_pic[]" id="my_ipt_pic">
          </fieldset>
          <div id="my_preview">
            <div class="ey-img-rotator">
              <input type="hidden" name="_deg" value="0" class="ey-rotator-deg">
              <a class="btn btn-link ey-rotator-btn" id="my_btn_rotate"><i class="icon-repeat"></i></a>
              <img class="img-polaroid ey-rotator-img" id="my_preview_img" src="img/what.jpg">
            </div>
          </div>
          <div class="progress progress-striped active ey-progress-form">
            <div class="bar"></div>
          </div>
          <div id="my_alertarea_upload"></div>
          <textarea name="_note" rows="3" placeholder="输入内容.."></textarea>
          <button class="btn" type="submit">上传</button>
        </form>
        <ul class="muted">
          <li><small>图片格式仅限JPG、GIF、PNG。</small></li>
          <li><small>图片大小为10K~500K。</small></li>
          <li><small>图片长宽比例适当。</small></li>
        </ul>
      </div>
      <div class="alert alert-success" id="my_pane_comm">
        <a class="close" href="javascript:closeComm()">&times;</a>
        <form method="POST" action="comm.php?_rw=w" id="my_form_comm">
          <input type="hidden" name="_t">
          <div class="progress progress-striped active ey-progress-form">
            <div class="bar"></div>
          </div>
          <div id="my_alertarea_comm"></div>
          <textarea name="_text" rows="3" placeholder="输入内容.."></textarea>
          <button class="btn" type="submit">发送</button>
        </form>
        <hr>
        <div class="progress progress-warning progress-striped active">
          <div class="bar"></div>
        </div>
        <ul class="unstyled" id="my_list_comm"></ul>
        <ul class="pager">
          <li class="previous" id="my_prev_comm"><a>&lt;</a></li>
          <li class="next" id="my_next_comm"><a>&gt;</a></li>
        </ul>
      </div>

      <div class="row" id="my_row_ups">
        {{foreach $cols as $col}}
        <div class="span{{12/count($cols)}} my-span" data-my-col="{{$col@key+1}}">
          {{foreach $col as $up}}
          <section class="my-up" id="my_up_{{$up['upid']}}">
            {{if $up['is_new']}}
            <div class="ribbon-wrapper">
              <div class="ribbon">NEW</div>
            </div>
            {{/if}}
            <article>
              {{if $up['is_gif']}}
              <div class="ey-gif-player">
                <a class="btn btn-link ey-gif-btn"><i class="icon-play"></i></a>
                <img class="ey-gif-preload" src="{{$dir_up}}/{{$up['uppic1']}}">
                <img class="img-rounded my-up-pic ey-gif-pic" src="{{$dir_up}}/{{$up['uppic']}}">
              </div>
              {{else}}
              <img class="img-rounded my-up-pic" src="{{$dir_up}}/{{$up['uppic']}}">
              {{/if}}
              <blockquote class="my-note">{{$up['upnote']}}</blockquote>
              <p class="my-publish">
                <span class="label label-info">来自</span><small>{{$up['upfrom']}}</small>
              </p>
            </article>
            <div class="my-alertarea-good"></div>
            <div class="btn-toolbar">
              <a class="btn" id="my_comm_{{$up['upid']}}" href="javascript:toggleComm({{$up['upid']}})">
                <i class="icon icon-comment"></i>
                <span class="my-count">{{$up['upcomms']}}</span>
              </a>
              <a class="btn my-btn-good{{if !$up['can_good']}} disabled{{/if}}"
              		href="javascript:{{if $up['can_good']}}good({{$up['upid']}}){{else}}return false{{/if}}">
                <i class="icon icon-thumbs-up"></i>
                <span class="my-count">{{$up['upgoods']}}</span>
              </a>
            </div>
          </section>
          {{/foreach}}
        </div> <!-- /.span -->
        {{/foreach}}
      </div> <!-- /.row -->

      <div class="pagination pagination-large" id="my_pagi">
        <ul>
          <li class="{{if $page==1}}disabled{{/if}}"><a href="{{query('_p=1')}}">&laquo;</a></li>
          {{if $page-1 >= 1}}
            {{if $page-2 >= 1}}
              {{if $page-3 >= 1}}
                <li class="disabled"><a>..</a></li>
              {{else}}
                <li><a href="{{query("_p={{$page-2}}")}}">{{$page-2}}</a></li>
              {{/if}}
            {{/if}}
            <li><a href="{{query("_p={{$page-1}}")}}">{{$page-1}}</a></li>
          {{/if}}
          <li class="disabled"><a>{{$page}}</a></li>
          {{if $page+1 <= $pages}}
          <li><a href="{{query("_p={{$page+1}}")}}">{{$page+1}}</a></li>
            {{if $page+2 <= $pages}}
              {{if $page+3 <= $pages}}
                <li class="disabled"><a>..</a></li>
              {{else}}
                <li><a href="{{query("_p={{$page+2}}")}}">{{$page+2}}</a></li>
              {{/if}}
            {{/if}}
          {{/if}}
          <li class="{{if $page==$pages}}disabled{{/if}}"><a href="{{query("_p={{$pages}}")}}">&raquo;</a></li>
        </ul>
      </div>
    </div> <!-- /.container -->

    <footer id="my_footer">
      <div class="container">
        <span class="muted credit">&copy; WuRuQiTu 2013</span>
      </div>
    </footer>

    <script src="min/?g=js&02150216"></script>
  </body>
</html>
