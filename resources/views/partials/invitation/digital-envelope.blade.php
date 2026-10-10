		<div class="elementor-element elementor-element-74f2aea7 e-con-full amplop-section e-flex e-con e-child" data-id="74f2aea7" data-element_type="container" data-e-type="container" data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
				<div class="elementor-element elementor-element-2942f7eb elementor-widget elementor-widget-spacer" data-id="2942f7eb" data-element_type="widget" data-e-type="widget" data-widget_type="spacer.default">
				<div class="elementor-widget-container">
							<div class="elementor-spacer">
			<div class="elementor-spacer-inner"></div>
		</div>
						</div>
				</div>
				<div class="idb-reveal idb-ef zoom-down elementor-element elementor-element-6a410462 elementor-widget__width-inherit elementor-widget elementor-widget-heading" data-reveal-offset="100" data-reveal-duration="2000" data-reveal-delay="0" style="transition-duration: 2000ms;" data-id="6a410462" data-element_type="widget" data-e-type="widget" data-widget_type="heading.default">
				<div class="elementor-widget-container">
					<h2 class="elementor-heading-title elementor-size-default">Amplop Digital</h2>				</div>
				</div>
				<div class="idb-reveal idb-ef zoom-down elementor-element elementor-element-973f656 elementor-widget__width-inherit elementor-widget elementor-widget-text-editor" data-reveal-offset="100" data-reveal-duration="2000" data-reveal-delay="0" style="transition-duration: 2000ms;" data-id="973f656" data-element_type="widget" data-e-type="widget" data-widget_type="text-editor.default">
				<div class="elementor-widget-container">
									Doa restu Anda merupakan karunia yang sangat berarti bagi kami, dan jika memberi adalah ungkapan tanda kasih, Anda dapat memberi kado secara cashless.								</div>
				</div>
				<div class="idb-reveal idb-ef zoom-down elementor-element elementor-element-2b80911c elementor-align-center elementor-widget elementor-widget-button" data-reveal-offset="100" data-reveal-duration="2000" data-reveal-delay="0" style="transition-duration: 2000ms;" data-id="2b80911c" data-element_type="widget" data-e-type="widget" id="klik" data-widget_type="button.default">
				<div class="elementor-widget-container">
									<div class="elementor-button-wrapper">
					<a class="elementor-button elementor-size-xs" role="button">
						<span class="elementor-button-content-wrapper">
									<span class="elementor-button-text">Klik disini</span>
					</span>
					</a>
				</div>
								</div>
				</div>
		<div class="elementor-element elementor-element-2547c74a e-con-full e-flex e-con e-child" data-id="2547c74a" data-element_type="container" data-e-type="container" id="amplop">
				<div class="elementor-element elementor-element-73170a48 elementor-widget elementor-widget-bisdev_copy_rekening" data-id="73170a48" data-element_type="widget" data-e-type="widget" data-widget_type="bisdev_copy_rekening.default">
				<div class="elementor-widget-container">
					<script>
            (function(){
              if (window.__idbBisdevEntranceZoomInit) return;
              window.__idbBisdevEntranceZoomInit = true;
              var selVisible = ".idb-copy-rek.elementor-invisible[data-settings], .idb-kirim-hadiah.elementor-invisible[data-settings]";
              var selAll = ".idb-copy-rek[data-settings], .idb-kirim-hadiah[data-settings]";
              var zoomCycle = 1;
              function parseSettings(el){
                var raw = el.getAttribute("data-settings") || "{}";
                try { return JSON.parse(raw); } catch(e){ return {}; }
              }
              function applyZoom(scope){
                var root = scope && scope.querySelectorAll ? scope : document;
                var items = root.querySelectorAll(selVisible);
                items.forEach(function(el){
                  if ((el.__idbZoomCycleApplied || 0) >= zoomCycle) return;
                  var st = parseSettings(el);
                  var anim = (st._animation || "zoomIn").toString();
                  var delay = parseInt(st._animation_delay || 0, 10) || 0;
                  if (!el.__idbEverShown) delay = Math.max(0, delay - 20);
                  if (el.offsetParent === null) return;
                  el.__idbZoomCycleApplied = zoomCycle;
                  el.__idbEverShown = true;
                  setTimeout(function(){
                    if ((el.__idbZoomCycleApplied || 0) !== zoomCycle) return;
                    el.classList.remove("elementor-invisible");
                    el.classList.add("animated", anim);
                  }, Math.max(0, delay));
                });
              }
              function replayZoom(scope){
                var root = scope && scope.querySelectorAll ? scope : document;
                var items = root.querySelectorAll(selAll);
                zoomCycle += 1;
                items.forEach(function(el){
                  if (el.offsetParent === null) return;
                  el.classList.remove("animated", "zoomIn");
                  el.classList.add("elementor-invisible");
                  el.__idbZoomCycleApplied = 0;
                });
                setTimeout(function(){ schedule(root); }, 40);
              }
              function syncVisibleStateAndReplay(scope){
                var root = scope && scope.querySelectorAll ? scope : document;
                var items = root.querySelectorAll(selAll);
                var shouldReplay = false;
                items.forEach(function(el){
                  var isVisible = (el.offsetParent !== null);
                  if (isVisible && !el.__idbWasVisible) {
                    el.__idbWasVisible = true;
                    shouldReplay = true;
                  } else if (!isVisible && el.__idbWasVisible) {
                    el.__idbWasVisible = false;
                  }
                });
                if (shouldReplay) replayZoom(root);
              }
              var raf = 0;
              function schedule(scope){
                if (raf) cancelAnimationFrame(raf);
                raf = requestAnimationFrame(function(){ applyZoom(scope); });
              }
              document.addEventListener("DOMContentLoaded", function(){ schedule(document); });
              window.addEventListener("load", function(){ schedule(document); });
              document.addEventListener("app:content-ready", function(){ schedule(document); });
              document.addEventListener("app:navigated", function(){ schedule(document); });
              document.addEventListener("idb:rekening-shown", function(){ replayZoom(document); });
              if (window.elementorFrontend && window.elementorFrontend.hooks) {
                window.elementorFrontend.hooks.addAction("frontend/element_ready/global", function($scope){
                  try { schedule(($scope && $scope[0]) ? $scope[0] : document); } catch(e){ schedule(document); }
                });
              }
              var obs = new MutationObserver(function(){
                schedule(document);
                syncVisibleStateAndReplay(document);
              });
              obs.observe(document.documentElement, { attributes:true, childList:true, subtree:true, attributeFilter:["class","style"] });
              syncVisibleStateAndReplay(document);
              schedule(document);
            })();
            </script>                                <div class="idb-copy-rek idb-copy-rek--auto elementor-invisible is-stack is-btn-right is-hide-bankname" id="idb-copy-rek-73170a48-0"
                     data-settings='{&quot;_animation&quot;:&quot;zoomIn&quot;,&quot;_animation_delay&quot;:20}'
                     data-copy="12345678"
                     data-success="Tersalin."
                     data-fail="Gagal menyalin. Coba lagi."
                     data-trigger-text="0"
                     data-trigger-btn="1">
                    <div class="idb-copy-rek__box">
                        <div class="idb-copy-rek__info">
                                                            <div class="idb-copy-rek__label is-logo-right" >
                                                                                                                                                                <span class="idb-copy-rek__banklogo" style="margin-left:auto;"><img decoding="async" src="assets/vendor/site/wp-content/uploads/2026/06/BCA_5770.webp" alt="" loading="lazy"></span>
                                                                                                            </div>
                                                        
                            <div class="idb-copy-rek__number "
                                 >
                                                                    <div class="idb-copy-rek__chipimg"><img decoding="async" src="assets/vendor/site/wp-content/uploads/2026/06/chip-atm-1-2-1-1-1-3-1-1.png" alt="" loading="lazy"></div>
                                                                <div class="idb-copy-rek__numtext"><span class="no-rekening-marker" data-gift-index="0">12345678</span></div>
                            </div>

                                                            <div class="idb-copy-rek__name">Habib</div>
                                                    </div>

                                                    <button type="button" class="idb-copy-rek__btn">
                                                                    <span class="idb-copy-rek__icon idb-is-left"><i aria-hidden="true" class="fas fa-copy"></i></span>
                                                                <span class="idb-copy-rek__btntext">Copy</span>
                                                            </button>
                                                <div class="idb-copy-rek__toast" aria-live="polite" aria-atomic="true"></div>
                    </div>
                </div>
                				</div>
				</div>
				<div class="elementor-element elementor-element-f13fb2 elementor-widget elementor-widget-bisdev_kirim_hadiah" data-id="f13fb2" data-element_type="widget" data-e-type="widget" data-widget_type="bisdev_kirim_hadiah.default">
				<div class="elementor-widget-container">
					        <div class="idb-kirim-hadiah idb-kirim-hadiah--auto elementor-invisible" data-settings="{&quot;_animation&quot;:&quot;zoomIn&quot;,&quot;_animation_delay&quot;:20}">
            <div class="idb-kirim-hadiah__box">
                <div class="idb-kirim-hadiah__icon" aria-hidden="true">
                    <i aria-hidden="true" class="fas fa-gift"></i>                </div>

                                    <div class="idb-kirim-hadiah__title">Kirim Hadiah</div>
                
                <div class="idb-kirim-hadiah__content">
                                    <div class="idb-kirim-hadiah__line">
                        <span class="idb-kirim-hadiah__label">Nama Penerima</span>
                        <span class="idb-kirim-hadiah__sep">:</span>
                        <span class="idb-kirim-hadiah__value">Habib Yulianto</span>
                    </div>
                
                                    <div class="idb-kirim-hadiah__line">
                        <span class="idb-kirim-hadiah__label">No. HP</span>
                        <span class="idb-kirim-hadiah__sep">:</span>
                        <span class="idb-kirim-hadiah__value">1234567890</span>
                    </div>
                
                                    <div class="idb-kirim-hadiah__line idb-kirim-hadiah__line--alamat">
                        <span class="idb-kirim-hadiah__label">Alamat</span>
                        <span class="idb-kirim-hadiah__sep">:</span>
                        <span class="idb-kirim-hadiah__value">Ds Pagu Kec.Wates Kab. Kediri</span>
                    </div>
                                                    </div>

                            </div>
        </div>
        				</div>
				</div>
				</div>
				<div class="elementor-element elementor-element-5a966266 elementor-widget elementor-widget-spacer" data-id="5a966266" data-element_type="widget" data-e-type="widget" data-widget_type="spacer.default">
				<div class="elementor-widget-container">
							<div class="elementor-spacer">
			<div class="elementor-spacer-inner"></div>
		</div>
						</div>
				</div>
				</div>
