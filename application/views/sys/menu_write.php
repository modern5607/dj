<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="body_cont_float">
	<ul>
		<li>
			<div class="formBox">
				<h3>메뉴추가</h3>
				<form method="post" name="menuUpdateForm" action="<?php echo base_url('SYS/menu_update');?>">
					<input type="hidden" name="IDX" value="" />
					<!--select>
						<option>:::대메뉴선택:::</option>
					</select-->
					<p>
						<label>- 대메뉴명</label>
						<input type="text" name="MENU_GROUP" value="" />
					</p>
					<p>
						<label>- 소메뉴코드</label>
						<input type="text" name="MENUID" value="" />
					</p>
					<p>
						<label>- 소메뉴명</label>
						<input type="text" name="MENUNAME" value="" />
					</p>
					<p>
						<label>- 권한지정</label>
						<input type="text" name="LEVEL" value="" />
					</p>
					<p>
						<label>
						<input type="radio" name="USE_YN" value="Y" /> 사용
						</label>
						<label>
						<input type="radio" name="USE_YN" value="N" /> 미사용
						</label>
					</p>
					<p>
						<label>- 비고</label>
						<input type="text" name="REMARK" value=""/>
					</p>
					<p>
						<input type="submit" value="메뉴등록">
					</p>
				</form>
			</div>
		</li>
		<li>
			
			<div class="tbl-content">
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
				<thead>
					<tr>
						<th>대메뉴</th>
						<th>소메뉴코드</th>
						<th>소메뉴명</th>
						<th>권한</th>
						<th>사용유무</th>
						<th>비고</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
				<?php foreach($list as $menu){ ?>
					<tr>
						<td><?php echo $menu->MENU_GROUP;?></td>
						<td><?php echo $menu->MENUID;?></td>
						<td><?php echo $menu->MENUNAME;?></td>
						<td class="cen"><?php echo $menu->LEVEL;?></td>
						<td class="cen" data-code="<?php echo $menu->USE_YN;?>"><?php echo ($menu->USE_YN == "Y")?"사용":"미사용";?></td>
						<td class="cen"><?php echo $menu->REMARK;?></td>
						<td class="cen"><span class="btn menu_mod" data-idx="<?php echo $menu->IDX;?>">수정</span></td>
					</tr>
				<?php } ?>
				</tbody>
			</table>
			</div>
		
		
			

		</li>
	</ul>
</div>


<script type="text/javascript">
<!--
$(".menu_mod").on("click",function(){
	
	$("input[name='IDX']").val($(this).data("idx"));
	$("input[name='MENU_GROUP']").val($(this).parents("tr").find("td:eq(0)").text());
	$("input[name='MENUID']").val($(this).parents("tr").find("td:eq(1)").text());
	$("input[name='MENUNAME']").val($(this).parents("tr").find("td:eq(2)").text());
	$("input[name='LEVEL']").val($(this).parents("tr").find("td:eq(3)").text());
	$("input:radio[name='USE_YN']:radio[value='"+$(this).parents("tr").find("td:eq(4)").data('code')+"']").prop("checked",true);
	$("input[name='REMARK']").val($(this).parents("tr").find("td:eq(5)").text());

});
//-->
</script>
