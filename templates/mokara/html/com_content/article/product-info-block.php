	<div class="col-xs-12 col-sm-6 col-md-6 ed-media-block">
			
					
				<div class="row">
					<div class="col-xs-12 col-sm-2 thumb-list">
						<?php foreach ($this->item->jcfields as $field) { ?>
							<?php if ($field->id > 23 &&  $field->id < 28) {?>
								<div class="thumb_img">
									
									<img class="" src="<?php echo $field->rawvalue?>" alt="<?php echo $this->item->title?>"/>
								</div>
							<?php }}?>
					</div>
					<div class="col-xs-12 col-sm-10" id="main_image">
						<img itemprop="image" class="main-img" src="<?php echo $this->item->product_thumb;?>" alt="<?php echo $this->item->title?>"/>
						
					
					</div>
					
				</div>
					<script>
					jQuery(function($) {
						$('.thumb_img').click(function(){
							var imgelem = $(this).find('img').attr('src');
						
							$('#main_image').html('<img src="'+imgelem+'"/>' );

						});

						});
				</script>
			
				
			</div>
			<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 ed-shopping-block">
				
				
				
				<h1 class="product-title-detail">
					<span itemprop="name"><?php echo $this->escape($this->item->title); ?></span> (<span itemprop="mpn"><?php echo $this->item->sku?></span>)
				</h1>
					<?php echo $this->item->event->afterDisplayTitle; ?>
			
				
				<strong>Danh mục: </strong><a class="more-product" data-toggle="tooltip" title="Xem thêm các sản phẩm trong danh mục <?php echo $category->title?>" href="<?php echo JRoute::_('index.php?option=com_content&view=category&layout=blog&id='.$this->item->catid)?>"><?php echo $category->title?></a>
				
				<?php foreach ($this->item->jcfields as $field) : ?>
					<?php if ($field->id > 7 && $field->id != 14 && $field->value && $field->id < 24) {?>
					<?php $description .= ' | '.$field->label.': '.$field->value;?>
					
					
				
					
					<?php 
					
					if (is_array($field->rawvalue)) {
						$field_value = explode(", ",$field->value);
						$c=array_combine($field->rawvalue,$field_value);
						echo '<div class="product-custom-field"><strong>'.$field->label . ': </strong>' ;
						foreach ($c as $key=>$value) {
							$link = 'index.php?option=com_content&filter_tag='.$key.'&id='.$this->item->catid.'&lang=en&layout=blog&view=category';
							
							$link = $productMod->get_alias_url($link);
							echo ' <a class="more-product" data-toggle="tooltip" title="Xem thêm các sản phẩm '.$category->title.' cùng '.$field->label . ': '.$value.'" href="'.$link.'">'.$value.'</a> ';
						}
						echo '</div>';
					}else {
						echo '<div class="product-custom-field"><strong>'.$field->label . ': </strong>' ;
						$link = 'index.php?option=com_content&filter_tag='.$field->rawvalue.'&id='.$this->item->catid.'&lang=en&layout=blog&view=category';
							$link = $productMod->get_alias_url($link);
						echo ' <a class="more-product" data-toggle="tooltip" title="Xem thêm các sản phẩm '.$category->title.' cùng '.$field->label . ': '.$field->value.'" href="'.$link.'">'.$field->value.'</a> ';
						echo '</div>';
					}
					
					?>
					<?php }?>
				<?php endforeach ?>
			
				<?php 
				
					 
						
				?>
				
			
				<div itemprop="offers" itemscope itemtype="http://schema.org/Offer">
				<meta itemprop="priceCurrency" content="VND" />
					<?php if ($this->item->product_old_price) {?>
						<div class="old_price"><strong><?php echo JText::_('COM_CONTENT_OLD_PRICE'); ?>: </strong><s><?php echo $productMod->ed_number_format($this->item->product_old_price)?></s></div>
					<?php }?>
					<div class="price <?php if ($this->item->deal_active) echo "active_deal"?>">
						<strong><?php echo JText::_('COM_CONTENT_PRICE');?>: </strong> 
						<span class="detail_price"><?php echo $productMod->ed_number_format($this->item->product_price)?></span>
					</div>
					<?php if ($this->item->hot_deal) {?>
					<div id="myModal2" class="modal fade" role="dialog">
									  <div class="modal-dialog">

										<!-- Modal content-->
										<div class="modal-content">
										 
										  <div class="modal-body text-center">
											<h3 >Vui lòng để lại số điện thoại, chúng tôi sẽ liên lạc lại với quý khách khi deal này bắt đầu.</h3>
											<form action="#" method="post" name="leave_phone">
											<input type="hidden" name="product_id" value="<?php echo $this->item->id?>"/>
											<input type="hidden" name="option" value="com_content"/>
											<input type="hidden" name="view" value="article"/>
											<input type="text" class="form-control" name="phone_leave" placeholder="Ví dụ: 0912-345-678" value="<?php echo $userProfile->profile['phone']?>"/><br/>
											<input type="text" class="form-control" name="name_leave" placeholder="Ví dụ: Nguyễn Thị A" value="<?php echo $user->name?>"/><br/>
											<button type="submit" name="submit" class="btn btn-black"><?php echo JText::_('COM_CONTENT_LEAVE_PHONE')?></button>
											</form>
										  </div>
										  <div class="modal-footer">
											<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
										  </div>
										</div>

									  </div>
									</div>
					
					<form action="<?php echo JRoute::_('index.php?option=com_mokara&view=orders&Itemid=502')?>" method="post" class="buy-section">
					<?php if ($this->item->deal_active) {?>
					<div class="size inline-block">
					<select name="size" required class="form-control select-size">
											<option value="">Vui lòng chọn Size</option>
											<option value="S">S</option>
											<option value="M">M</option>
											<option value="L">L</option>
											<option value="XL">XL</option>
										</select>
										</div>
					<?php }?>					
					<?php date_default_timezone_set('Asia/Ho_Chi_Minh');?>
					<div class="clear"></div>
					<div class="deal-block">
						<h3>Hot Deal</h3>
						<div class="deal-info">
							<?php echo $this->item->deal_info?>
						</div>
						<div class="deal_price">
							<?php if ($this->item->deal_active) {?>
								<?php echo JText::_('COM_CONTENT_DEAL_WILL_BE_END');?>:<br/>
								<?php if ($this->item->hot_deal_type == 2) {?>
									<?php  										
										$deal_stop_temporary = date("Y-m-d");
										$deal_stop_temporary.=" ".$this->item->deal_time_end; 
									?>
								<?php } else {?>
									<?php  	
										
										
										$deal_stop_temporary = $this->item->deal_date_end." ".$this->item->deal_time_end;
										
									?>
								<?php }?>
								
								
							<?php } else {?>
								<?php echo JText::_('COM_CONTENT_DEAL_WILL_BE_START');?>:<br/>
								<?php if ($this->item->hot_deal_type == 2) {?>
									<?php 
										if ($this->item->deal_day) {
											switch ($this->item->deal_day) {
												case 1:
													$next_day = "next Monday";
													break;
												case 2:
													$next_day = "next Tuesday";
													break;
												case 3:
													$next_day = "next Wednesday";
													break;
												case 4:
													$next_day = "next Thursday";
													break;
												case 5:
													$next_day = "next Friday";
													break;
												case 6:
													$next_day = "next Saturday";
													break;
												case 7:
													$next_day = "next Sunday";
													break;	
											}
											$deal_stop_temporary = date("Y-m-d", strtotime($next_day));
											
										}else {
											$datetime = new DateTime('tomorrow');
											$deal_stop_temporary = $datetime->format('Y-m-d');
											
										}
										$deal_stop_temporary.=" ".$this->item->deal_time_start; 
										
									?>
								<?php } else {?>
									<?php 
										$deal_stop_temporary = $this->item->deal_date_start." ".$this->item->deal_time_start;
									?>
								<?php }?>	
							<?php }?>
							<i class="fa fa-clock-o" aria-hidden="true"></i> <p id="countdown"></p>
							<br/>
							
							<span class="detail_price"><?php echo $productMod->ed_number_format($this->item->deal_price)?></span>
									
									<?php if ($this->item->deal_active) {?>
									<br/>
										<button type="submit" name="submit" class="btn btn-buy"><i class="fa fa-shopping-cart"></i> <?php echo JText::_('COM_CONTENT_ADD_TO_CART')?></button>
										
									<?php } else {?>
									<br/>
										<button type="button" class="btn btn-black" data-toggle="modal" data-target="#myModal2"><i class="fa fa-phone-square" aria-hidden="true"></i> Nhắc tôi khi deal bắt đầu</button>
										
									<?php }?>			
										
									
										
										
										
										
										 <input type="hidden"  name="quantity" value="1" />
										
									
							
							
								<input type="hidden" name="product_id" value="<?php echo $this->item->id?>"/>
								<input type="hidden" name="option" value="com_mokara"/>
								<input type="hidden" name="hot_deal" value="1"/>
								<input type="hidden" name="view" value="orders"/>
								<input type="hidden" name="task" value="add2cart"/>
								<input type="hidden" name="Itemid" value="502"/>
								<input type="hidden" name="product_name" value="<?php echo $this->item->title?>"/>
								<?php if ($this->item->save_money) {?>
								<input type="hidden" name="save_money_value" value="<?php echo $this->item->save_money_value?>"/>
								<?php }?>
								<input type="hidden" name="product_img" value="<?php echo $this->item->product_thumb?>"/>
								<input type="hidden" name="product_price" value="<?php echo $this->item->deal_price?>"/>
								<input type="hidden" name="product_old_price" value="<?php echo $this->item->product_price?>"/>
								<input type="hidden" name="product_category_id" value="<?php echo $this->item->catid?>"/>
							
						</div>
						
					</div>
					</form>
				
					<?php }?>
					<span itemprop="seller" itemscope itemtype="http://schema.org/Organization" class="hidden">
                      <span itemprop="name">Mokara</span>
					 </span> 
					  <link itemprop="itemCondition" href="http://schema.org/New"/>
					  <div class="stock">
						<strong>Trạng thái:</strong> <?php if ($this->item->product_status == 1) echo "Còn hàng"; else echo "Hết hàng"?>
						<link itemprop="availability" href="http://schema.org/<?php if ($this->item->product_status == 1) echo "InStock"; else echo "OutOfStock"?>"/>
					</div>
				</div>	
					
				
					<br/>
						<div class="fb-like" data-href="<?php echo JUri::getInstance();?>" data-layout="button_count" data-action="like" data-size="large" data-show-faces="true" data-share="true"></div>
						<div class="fb-save" data-uri="<?php echo JUri::getInstance();?>"></div>
						<div class="fb-send" data-href="<?php echo JUri::getInstance();?>"></div>
						
			</div><!--END ART TO CART SECTION-->
			<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 ed-loyalty-block hidden">
				<div class="ed-loyalty-inner">
				<img src="images/Special-Offer-Banner.png" alt="Ưu đãi đặc biệt" class="special-banner hidden-xs">
				<h3 class="text-center">Ưu đãi đặc biệt</h3>
					<ul class="special-list">
					<li>Tặng ngay <span>50.000<sup>đ</sup></span> vào tài khoản. <a href="">Xem chi tiết!</a></li>
					<li>Nhận ngay <span>2</span> mã số dự thưởng may mắn. <a href="">Xem chi tiết!</a> </li>
					<li class="margin-top-10">Giao hàng tận nơi miễn phí trên toàn quốc. <a href="">Xem chi tiết!</a> </li>
					<li class="margin-top-10">1 đổi 1 trong 1 tháng với sản phẩm lỗi. <a href="">Xem chi tiết!</a></li>
					</ul>
					</div>
			</div>
				<script>
								// Set the date we're counting down to
								var countDownDate = new Date("<?php echo $deal_stop_temporary?>").getTime();

								// Update the count down every 1 second
								var x = setInterval(function() {

								  // Get todays date and time
								  var now = new Date().getTime();
								 
								  // Find the distance between now an the count down date
								  var distance = countDownDate - now;

								  // Time calculations for days, hours, minutes and seconds
								  var days = Math.floor(distance / (1000 * 60 * 60 * 24));
								  var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
								  var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
								  var seconds = Math.floor((distance % (1000 * 60)) / 1000);
									var count_time = "";
								  // Display the result in the element with id="demo"
								  if (days > 0) {
									   count_time = count_time + days + " ngày ";
								  }
								  if (hours > 0) {
									  count_time = count_time + hours + " giờ ";
								  }
								  if (minutes > 0) {
									  count_time = count_time + minutes + " ' ";
								  }
								  count_time = count_time + seconds + " '' ";
								  document.getElementById("countdown").innerHTML = count_time;

								  // If the count down is finished, write some text 
								  if (distance < 0) {
									clearInterval(x);
									document.getElementById("countdown").innerHTML = "EXPIRED";
								  }
								}, 1000);
								</script>		
			