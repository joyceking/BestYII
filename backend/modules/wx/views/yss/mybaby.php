<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no"/> 
        <title>爱迪天才宝贝相册</title>
        <link href="site-assets/site.css?v=4.0.6-1.0.4" rel="stylesheet">
        <link href="dist/photoswipe.css?v=4.0.6-1.0.4" rel="stylesheet">
        <link href="dist/default-skin/default-skin.css?v=4.0.6-1.0.4" rel="stylesheet">
        <script src="dist/photoswipe.min.js?v=4.0.6-1.0.4"></script><style type="text/css" adt="123"></style>
        <script src="dist/photoswipe-ui-default.min.js?v=4.0.6-1.0.4"></script>
        <!--[if lt IE 9]>
         <script>
            document.createElement('figure');
         </script>
        <![endif]-->
        <style type="text/css">
            .my-simple-gallery {
                width: 100%;
                float: left;
            }
            .my-simple-gallery img {
                width: 100%;
                padding:2px;
                height: auto;
            }
            .my-simple-gallery figure {
                display: block;
                float: left;
                width: 33.333333%;
            }
            .my-simple-gallery figcaption {
                display: none;
            }
        </style>
    </head>
    <body>
        <div class="my-simple-gallery">
            <?php

            use yii\helpers\Url;
            use common\models\MSchool;
            use common\models\MSchoolBranch;
            use common\models\MTeacher;
            use common\models\MPhoto;
            use common\models\MPhotoOwner;

foreach ($student_ids as $student) {
                $photos = MPhotoOwner::getPhotosBySignon(MPhotoOwner::PHOTO_OWNER_STUDENT, $student, 100);
                foreach ($photos as $photo) {
                    $picUrl = Url::to($photo->photo->getPicUrl());
                    $img_info = getimagesize($picUrl);
                    ?>
                    <figure>
                        <a href="<?php echo $picUrl ?>" itemprop="contentUrl" data-size="<?php echo $img_info[0] ?>x<?php echo $img_info[1] ?>">
                            <img src="<?php echo Url::to($photo->photo->getPicUrl(150, 150)) ?>" width="33.333333%"itemprop="thumbnail" alt="<?= $photo->photo->des; ?>" />
                        </a>
                        <figcaption itemprop="caption description"><?= $photo->photoBySignon->memo; ?></figcaption>
                    </figure>
                    <?php
                }
            }
            ?>
        </div>
        <div id="gallery" class="pswp" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="pswp__bg"></div>
            <div class="pswp__scroll-wrap">
                <div class="pswp__container">
                    <div class="pswp__item"></div>
                    <div class="pswp__item"></div>
                    <div class="pswp__item"></div>
                </div>
                <div class="pswp__ui pswp__ui--hidden">
                    <div class="pswp__top-bar">
                        <div class="pswp__counter"></div>
                        <button class="pswp__button pswp__button--close" title="Close (Esc)"></button>
                        <button class="pswp__button pswp__button--share" title="Share"></button>
                        <button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button>
                        <button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>
                        <div class="pswp__preloader">
                            <div class="pswp__preloader__icn">
                                <div class="pswp__preloader__cut">
                                    <div class="pswp__preloader__donut"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="pswp__loading-indicator"><div class="pswp__loading-indicator__line"></div></div> -->
                    <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
                        <div class="pswp__share-tooltip">
                            <!-- <a href="#" class="pswp__share--facebook"></a>
                            <a href="#" class="pswp__share--twitter"></a>
                            <a href="#" class="pswp__share--pinterest"></a>
                            <a href="#" download class="pswp__share--download"></a> -->
                        </div>
                    </div>
                    <button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)"></button>
                    <button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)"></button>
                    <div class="pswp__caption">
                        <div class="pswp__caption__center">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
        <script type="text/javascript">
            var shareData, shareData1;
            wx.config({
                appId: '<?php echo $signPackage["appId"]; ?>',
                timestamp: '<?php echo $signPackage["timestamp"]; ?>',
                nonceStr: '<?php echo $signPackage["nonceStr"]; ?>',
                signature: '<?php echo $signPackage["signature"]; ?>',
                jsApiList: [
                    // 所有要调用的 API 都要加到这个列表中
                    'onMenuShareTimeline',
                    'onMenuShareAppMessage',
                    'onMenuShareQQ',
                    'onMenuShareWeibo'
                ]
            });
            shareData1 = {
                title: '我家宝贝的萌照',
                desc: '<?= $photo->photoBySignon->memo; ?>',
                link: 'http://b.idealangel.cn/index.php?r=wx/yss/mybaby&gh_id=<?php echo $gh_id; ?>&openid=nobody&student_ids=<?php echo $student_id; ?>',
                imgUrl: '<?php echo Url::to($photo->photo->getPicUrl()) ?>'
            };
            wx.ready(function () {
                wx.onMenuShareAppMessage(shareData1);
                wx.onMenuShareTimeline(shareData1);
            });
            var initPhotoSwipeFromDOM = function (gallerySelector) {

                // parse slide data (url, title, size ...) from DOM elements 
                // (children of gallerySelector)
                var parseThumbnailElements = function (el) {
                    var thumbElements = el.childNodes,
                            numNodes = thumbElements.length,
                            items = [],
                            figureEl,
                            childElements,
                            linkEl,
                            size,
                            item;
                    for (var i = 0; i < numNodes; i++) {
                        figureEl = thumbElements[i]; // <figure> element

                        // include only element nodes 
                        if (figureEl.nodeType !== 1) {
                            continue;
                        }

                        linkEl = figureEl.children[0]; // <a> element

                        size = linkEl.getAttribute('data-size').split('x');

                        // create slide object
                        item = {
                            src: linkEl.getAttribute('href'),
                            w: parseInt(size[0], 10),
                            h: parseInt(size[1], 10)
                        };



                        if (figureEl.children.length > 1) {
                            // <figcaption> content
                            item.title = figureEl.children[1].innerHTML;
                        }

                        if (linkEl.children.length > 0) {
                            // <img> thumbnail element, retrieving thumbnail url
                            item.msrc = linkEl.children[0].getAttribute('src');
                        }

                        item.el = figureEl; // save link to element for getThumbBoundsFn
                        items.push(item);
                    }

                    return items;
                };

                // find nearest parent element
                var closest = function closest(el, fn) {
                    return el && (fn(el) ? el : closest(el.parentNode, fn));
                };

                // triggers when user clicks on thumbnail
                var onThumbnailsClick = function (e) {
                    e = e || window.event;
                    e.preventDefault ? e.preventDefault() : e.returnValue = false;

                    var eTarget = e.target || e.srcElement;

                    var clickedListItem = closest(eTarget, function (el) {
                        return (el.tagName && el.tagName.toUpperCase() === 'FIGURE');
                    });

                    if (!clickedListItem) {
                        return;
                    }


                    // find index of clicked item
                    var clickedGallery = clickedListItem.parentNode,
                            childNodes = clickedListItem.parentNode.childNodes,
                            numChildNodes = childNodes.length,
                            nodeIndex = 0,
                            index;

                    for (var i = 0; i < numChildNodes; i++) {
                        if (childNodes[i].nodeType !== 1) {
                            continue;
                        }

                        if (childNodes[i] === clickedListItem) {
                            index = nodeIndex;
                            break;
                        }
                        nodeIndex++;
                    }



                    if (index >= 0) {
                        openPhotoSwipe(index, clickedGallery);
                    }
                    return false;
                };

                // parse picture index and gallery index from URL (#&pid=1&gid=2)
                var photoswipeParseHash = function () {
                    var hash = window.location.hash.substring(1),
                            params = {};

                    if (hash.length < 5) {
                        return params;
                    }

                    var vars = hash.split('&');
                    for (var i = 0; i < vars.length; i++) {
                        if (!vars[i]) {
                            continue;
                        }
                        var pair = vars[i].split('=');
                        if (pair.length < 2) {
                            continue;
                        }
                        params[pair[0]] = pair[1];
                    }

                    if (params.gid) {
                        params.gid = parseInt(params.gid, 10);
                    }

                    if (!params.hasOwnProperty('pid')) {
                        return params;
                    }
                    params.pid = parseInt(params.pid, 10);
                    return params;
                };

                var openPhotoSwipe = function (index, galleryElement, disableAnimation) {
                    var pswpElement = document.querySelectorAll('.pswp')[0],
                            gallery,
                            options,
                            items;
                    items = parseThumbnailElements(galleryElement);

                    // define options (if needed)
                    options = {
                        index: index,
                        // define gallery index (for URL)
                        galleryUID: galleryElement.getAttribute('data-pswp-uid'),
                        getThumbBoundsFn: function (index) {
                            // See Options -> getThumbBoundsFn section of docs for more info
                            var thumbnail = items[index].el.getElementsByTagName('img')[0], // find thumbnail
                                    pageYScroll = window.pageYOffset || document.documentElement.scrollTop,
                                    rect = thumbnail.getBoundingClientRect();

                            return {x: rect.left, y: rect.top + pageYScroll, w: rect.width};
                        },
                        // history & focus options are disabled on CodePen
                        // remove these lines in real life: 
                        history: true,
                        shareEl: false,
                        tapToToggleControls: false,
                        focus: false

                    };
                    if (disableAnimation) {
                        options.showAnimationDuration = 0;
                    }
                    // Pass data to PhotoSwipe and initialize it
                    gallery = new PhotoSwipe(pswpElement, PhotoSwipeUI_Default, items, options);
                    gallery.listen('afterChange', function () {
                        //hashData1 = photoswipeParseHash();
                        shareData = {
                            title: gallery.currItem.title,
                            desc: gallery.currItem.title,
                            link: 'http://b.idealangel.cn/index.php?r=wx/yss/mybaby&gh_id=<?php echo $gh_id; ?>&openid=nobody&student_ids=<?php echo $student_id; ?>#&gid=1&pid=' + (gallery.getCurrentIndex() + 1),
                            imgUrl: gallery.currItem.src
                        };
                        //alert(gallery.isDragging());
                        wx.ready(function () {
                            wx.onMenuShareAppMessage(shareData);
                            wx.onMenuShareTimeline(shareData);
                        });
                    });
                    gallery.listen('destroy', function () {
                        wx.ready(function () {
                            wx.onMenuShareAppMessage(shareData1);
                            wx.onMenuShareTimeline(shareData1);
                        });
                    });
                    gallery.init();
                };

                // loop through all gallery elements and bind events
                var galleryElements = document.querySelectorAll(gallerySelector);

                for (var i = 0, l = galleryElements.length; i < l; i++) {
                    galleryElements[i].setAttribute('data-pswp-uid', i + 1);
                    galleryElements[i].onclick = onThumbnailsClick;
                }

                // Parse URL and open gallery if it contains #&pid=3&gid=1
                var hashData = photoswipeParseHash();
                if (hashData.pid > 0 && hashData.gid > 0) {
                    openPhotoSwipe(hashData.pid - 1, galleryElements[ hashData.gid - 1 ], true);
                }
            };

// execute above function
            initPhotoSwipeFromDOM('.my-simple-gallery');
        </script>
    </body>
</html>
<?php
