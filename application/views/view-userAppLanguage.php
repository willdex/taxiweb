<!DOCTYPE html>
<html>
  <?php
	 include "includes/admin_header.php";
	 ?>
	 <link rel="stylesheet" href="<?php echo base_url();?>assets/adminlte/plugins/datatables/dataTables.bootstrap.css">
  <body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">
      <!-- Left side column. contains the logo and sidebar -->
     <?php
	 include "includes/admin_sidebar.php";
	 ?>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
           <div class="col-lg-12">
                    <h1 class="page-header">Add User Apps Language Details</h1>

                </div>
          <ol class="breadcrumb">
            <li><a href="<?php echo base_url();?>admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">PM</a></li>
            <li class="active">View All</li>
          </ol>
        </section>



        <section class="content">
          <div class="row">
            <div class="col-xs-12">
            <div class="box">
              <div class="box-header">

              </div><!-- /.box-header -->
              <div class="box-body">
                  <table id="example1" class="table table-bordered table-striped">
                   <?php  $query1 = $this->db->query("SELECT * FROM  user_app_language"); ?>
                      <thead>
                          <tr>
                              <th>ID</th>
                              <th>Language</th>
                             
                              <th>Set as App's Language</th>
                               <th>Status</th>
                              <th>Action</th>
                          </tr>
                      </thead>
                      <tbody>
                      <?php foreach($query1->result_array('userdetails') as $row1){
                        if($row1['status']==1){
                          $checkBox="checked";
                        }else{
                            $checkBox="";
                        }
                      ?>
                        <tr class="odd gradeX" >
                            <td class="center"> <?php echo $row1['id'];?></td>
                            <td class="center"> <?php echo $row1['language_name'];?></td>
                            
                            <td class="center">
                              <div style="padding-left:0px;">
                                  <div>
                                  <input type="radio" name="device_type" id="<?php echo $row1['id'];?>" <?php echo $checkBox; ?> value="vertical" onchange="setAppLanguage('<?php echo $row1['id'];?>','<?php echo $row1['language_name'];?>')"/>
                                  <label for="device_radio2"><?php echo $row1['language_name'];?></label>
                                  </div>
                              </div>
                            </td>
                            <td class="center"> <?php echo $row1['status'];?></td>
                            <td class="center"><a href="#"><i class="fa fa-pencil-square-o" onclick="editLanguage('<?php echo $row1['language_name'];?>')"></i></a>&nbsp;&nbsp;<a href="#" title="ttt" class="delete"><i class="fa fa-trash-o" onclick="deleteLang(<?php echo $row1['id'];?>)"></i></a></td>
                        </tr>
                     <?php } ?>

                  </table>
                </div>
              </div><!-- /.box -->
            </div>
          </div>
        </section>





        <!-- Main content -->
        <section class="content">
          <div class="row">



            <div class="col-xs-6">
            <div class="box box-default">
            <div class="box-header with-border">
              <h3 class="box-title">Add New Language</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="row">
                <div class="col-md-6">

                  <div class="form-group">
                    <label for="exampleInputEmail1">New Language</label>
                    <input type="text" class="form-control" id="new_lang" name="new_lang" placeholder="Enter New Language">
                  </div>
                  <input class="btn btn-primary" type="button" value="Add" id="newLang" >

                </div>


              </div>
              <!-- /.row -->
            </div>

          </div>
          </div>





            <div class="col-xs-6" id="EditModify">
            <div class="box box-default">
            <div class="box-header with-border">
              <h3 class="box-title">Select Language</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">

                    <label>Select language</label>
                    <select style="width: 100%;" class="form-control se" tabindex="-1" aria-hidden="true" id="languageSelect">
                      <option selected="selected">Select</option>
                      <?php foreach ($allLanguages as $key) {
                        ?>
                        <option value="<?php echo $key['language_name'];?>" ><?php echo $key['language_name'];?></option>
                        <?php
                      }
                       ?>
                    </select>
                  </div>


                  <!-- <div class="form-group">
                    <label>Set as default Language</label>
                    <div class="checkbox">
                      <label>
                        <input type="checkbox"> Set as default language
                      </label>
                    </div>
                  </div> -->
                  <!-- /.form-group -->

                </div>


              </div>
              <!-- /.row -->
            </div>

          </div>
        </div>












            <div class="col-xs-12">

            <form role="form" id="saveDriverLang" method="POST" action="saveUserApplang">
              <?php
               get_instance()->load->helper('language_helper');
               $languageForUserApp=getLanguageForUserApp();

              ?>

            <div class="col-lg-12">
              <div class="box box-primary edit_promoform">

                 <div class="box-body">
                  <?php
                  $i=0;
                  foreach ($languageForUserApp as $key => $value) {
                    if($i%2==0){
                      $style="width:40% !important;float:left;";
                    }else{
                      $style="width:40% !important;float:left; margin-left:50px;";
                    }
                  ?>

                     <div class="form-group" style="<?php echo $style;?>">
                       <label for="exampleInputEmail1"><?php echo $value; ?></label>
                       <input type="text" class="form-control languageForm" value='' id="<?php echo $key; ?>" name="<?php echo $key; ?>" placeholder="Enter for <?php echo $value; ?>" required>
                     </div>
                   <?php
                   $i++;
                   }
                  ?>

                 </div>
                 <input type="hidden" id="hidden_lang" name="hidden_lang" val="" required>
                 <input class="btn btn-primary" type="submit" value="Save" style="margin-left:1%;">
              </div>
            </div>
          </form>




            </div>   <!-- col-xs-12 -->
          </div><!-- /.row -->
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
     <?php
	 include "includes/admin-footer.php";
	 ?>


    </div><!-- ./wrapper -->


    <!-- jQuery 2.1.4 -->
    <script src="<?php echo base_url();?>assets/adminlte/plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <!-- Bootstrap 3.3.5 -->
    <script src="<?php echo base_url();?>assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
    <!-- DataTables -->
    <script src="<?php echo base_url();?>assets/adminlte/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="<?php echo base_url();?>assets/adminlte/plugins/datatables/dataTables.bootstrap.min.js"></script>
    <!-- SlimScroll -->
    <script src="<?php echo base_url();?>assets/adminlte/plugins/slimScroll/jquery.slimscroll.min.js"></script>
    <!-- FastClick -->
    <script src="<?php echo base_url();?>assets/adminlte/plugins/fastclick/fastclick.min.js"></script>
    <!-- AdminLTE App -->
    <script src="<?php echo base_url();?>assets/adminlte/dist/js/app.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="<?php echo base_url();?>assets/adminlte/dist/js/demo.js"></script>
    <!-- page script -->
   <script>
      $(function () {
        $("#example1").DataTable({
			"ordering": false
			});
      });
    </script>
	<script>


    $('#languageSelect').on('change', function() {
    //  alert("hai");
      //alert( this.value );
      if(this.value != 'Select')
      {
        var fetchLanguage=this.value;
          $('#hidden_lang').val(fetchLanguage);
        $.ajax({
					url:'<?php echo base_url();?>admin/showStoredLanguage',
					type:'post',
					data:{'fetchLanguage':fetchLanguage,'app':'user'},
					success:function(data){
            var obj = JSON.parse(data);
            //console.log(obj);
            if(obj!=null){
              var totalJsondata=Object.keys(obj).length;
              for (var key in obj) {
                if (obj.hasOwnProperty(key)) {
                   $('#'+key).val(obj[key]);
                //  console.log(key + " -> " + obj[key]);
                }
              }
            }else {

              $('.languageForm').val('');
            }


					}
				});
        //alert(this.value );
      }else{
        $('#hidden_lang').val("");
          $('.languageForm').val('');
      }
    })

    $('#newLang').on('click', function(){
      //alert();
      if($('#new_lang').val().trim() != ""){
          var newLanguage=$('#new_lang').val();
          $.ajax({
  					url:'<?php echo base_url();?>admin/saveNewLanguage',
  					type:'post',
  					data:{'newLanguage':newLanguage,'app':'user'},
  					success:function(data){
             //console.log(data);
             if(data==1){
               location.reload();
             }

  					}
  				});
      }
    })

    function deleteLang(id){
      var r = confirm("Do you want to delete? ");
      if (r == true) {
        $.ajax({
          url:'<?php echo base_url();?>admin/deleteUserAppLanguage',
          type:'post',
          data:{'id':id},
          success:function(data){
            if(data==0){
              alert("Sorry Could't delete the record");
            }else{
              alert("deleted Successfully");
              location.reload();
            }
          }
        });
      }

    }

    function editLanguage(lan){
      $('#languageSelect').val(lan).change();
      //EditModify
      $("html, body").animate({ scrollTop: $('#EditModify').offset().top -20}, "slow");
    }

    var $form = $( '#saveDriverLang' );
    $form.submit( function( event )
    {

      if($('#hidden_lang').val().trim()=='')
      {
        $('#languageSelect').css('border-color','red');
        $("html, body").animate({ scrollTop: $('#EditModify').offset().top -20}, "slow");
        return false;
      }
      //return false;
    });

    function setAppLanguage(id,language){
        var r = confirm("Do you want to Set '"+ language + "' as App Language?");
        if (r == true) {
          $.ajax({
            url:'<?php echo base_url();?>admin/setAppDefaultLanguage',
            type:'post',
            data:{'language':language,'app':'user'},
            success:function(data){
             if(data==1){
               location.reload();
             }else {
               alert("Could't set App Language");
             }

            }
          });
        }else {
            document.getElementById(id).checked = false;
        }
    }


    </script>

  </body>
</html>
