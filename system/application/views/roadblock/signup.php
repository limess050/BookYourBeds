<div class="row">
	<div class="span8">
		<?php echo form_open('signup', array('class' => 'form-horizontal')); ?>
			<fieldset>
				<legend>Create Your New Account</legend>

				<?php echo $template['partials']['form_errors']; ?>

				<div class="control-group">
		        	<label class="control-label">Email address</label>
		        	<div class="controls">
		            	<input type="text" class="span4" name="email" />
		            </div>
		        </div>

		        <div class="control-group">
		        	<label class="control-label">New Password</label>
		        	<div class="controls">
		            	<input type="password" class="span4" name="password" />
		            </div>
		        </div>

		        <div class="control-group">
		        	<label class="control-label">Account Name</label>
		        	<div class="controls">
		            	<input type="text" class="span3" name="name" />
		            </div>
		        </div>

		        <div class="control-group">
		        	<div class="controls">
		            	<div class="alert alert-success">
		            		By clicking 'Continue' below you are agreeing to the <strong>Book Your Beds</strong> <a data-toggle="modal" href="#myModal" >Terms and Conditions</a>.
						</div>
		            </div>
		        </div>
			</fieldset>

			<div class="form-actions">
		        <button type="submit" class="btn btn-primary">Continue</button>
		    </div>
		</form>
	</div>
</div>

<div class="modal hide" id="myModal"> 
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
    	<h3>Book Your Beds Terms and Conditions</h3>
  	</div>
  
  	<div class="modal-body">
    	<?php echo auto_typography('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc commodo nunc in leo aliquet non consequat erat ultricies. Vestibulum vitae ligula id mi ultricies cursus. Aenean turpis tellus, volutpat eu molestie vel, pharetra ac orci. Nullam ultricies nisl et urna blandit dictum. Cras ultrices semper arcu, sed facilisis lorem sodales sed. Curabitur sed velit at massa scelerisque posuere non sed tellus. Donec id fringilla diam. Nulla felis elit, vehicula in tristique a, mollis in sem.

Pellentesque urna orci, gravida ac fermentum et, elementum ut tellus. Donec ut mauris non tortor consequat varius ac sit amet libero. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Phasellus dolor mauris, sagittis non pellentesque et, facilisis nec libero. Nam eros nisl, feugiat sed feugiat vitae, viverra non elit. Maecenas sodales ornare nisi nec congue. Curabitur lobortis, ipsum sed congue convallis, augue nisl viverra enim, at posuere tellus felis a odio. Fusce quis risus quis massa placerat gravida. Nunc sed elit sit amet massa malesuada pellentesque. Phasellus eget libero eu neque mattis condimentum in vitae nunc. Sed vitae mi non erat facilisis rutrum. Etiam dignissim nulla vel magna luctus sed fringilla magna convallis. Praesent sodales, mauris gravida condimentum tincidunt, purus nibh malesuada odio, eget tristique dui magna id enim. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae;

Vivamus vestibulum ante dapibus odio sollicitudin tincidunt. Nam semper justo vitae urna porttitor ac porttitor risus sagittis. Pellentesque vel sapien risus, sit amet aliquam nisl. Sed sodales nunc lectus. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam erat volutpat. In congue vehicula odio, ac adipiscing magna vulputate nec. Duis fringilla, nulla sed vulputate tincidunt, turpis mauris ultrices velit, non commodo velit massa eget mi. Proin iaculis orci ut augue consequat porta.

Sed lobortis pellentesque lobortis. Nulla facilisi. Nam sit amet eros eu odio feugiat laoreet a ut nunc. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed interdum, dui eget vehicula vehicula, risus felis aliquet nisl, a rutrum tortor ipsum sed lacus. Integer in ipsum a velit lobortis tincidunt nec ac est. Etiam tincidunt pulvinar tincidunt. Etiam id dui at neque faucibus congue pulvinar et nibh. Etiam adipiscing turpis in lorem imperdiet vestibulum. Morbi non orci in neque adipiscing commodo in quis lectus. Morbi a ante sit amet enim venenatis consectetur quis non purus. In hendrerit nulla sed metus molestie hendrerit id sed arcu. Cras a risus vel lectus mollis interdum.

Morbi tortor lectus, facilisis volutpat laoreet sit amet, elementum in augue. Suspendisse potenti. Vestibulum magna leo, fringilla a dignissim at, venenatis eu augue. Nullam pharetra iaculis eros, nec tempor neque elementum ut. Suspendisse malesuada, dolor et pretium adipiscing, leo enim cursus nibh, non ullamcorper est quam viverra massa. Pellentesque vitae massa ante, sed ornare justo. Phasellus non sodales purus. Donec suscipit odio a mi faucibus a rhoncus nunc vulputate. Maecenas nec tempor leo. Duis at risus augue. Nunc sed ligula lacus, at congue lorem. Fusce commodo velit vel leo ultrices eu fringilla elit placerat.'); ?>
  	</div>
  
  	<div class="modal-footer">
    	<a href="#" class="btn btn-primary" data-dismiss="modal">OK</a>
  	</div>
</div>
