<?php
// Service
include_once("./services/member.service.php");
$memberService = new MemberService(true);
$getMember = $memberService->get();

if (@$_GET['update'] == 2) {
    $memberService->editMember(true, @$_GET['customerId']);
} else if (@$_GET['update'] == 1) {
    $memberService->editMember(false, @$_GET['customerId'], @$_GET['title'], @$_GET['firstname'], @$_GET['lastname'], @$_GET['idcard'], @$_GET['telephone']);
}
?>
<div class="dashboard-content">
    <table class="table table-hover" id="memberList">
        <thead>
            <tr>
                <th scope="col">รหัสลูกค้า</th>
                <th scope="col">ชื่อ-นามสกุล</th>
                <th scope="col">เบอร์โทรศัพท์</th>
                <th scope="col"><button type="button" onclick="addMember()" class="btn btn-primary"><i class="fas fa-user-plus"></i></button> <button onclick="openDelete()" type="button" class="btn btn-info"><i class="fas fa-search"></i></button></th>
            </tr>
        </thead>
        <tbody>
            <?php
            for ($i = 0; $i < count($getMember); $i++) {
                $member = $getMember[$i];
            ?>
                <tr>
                    <th scope="row"><?php echo $member['customerId']; ?></th>
                    <td><?php echo $member['title'] . $member['firstname'] . " " . $member['lastname']; ?></td>
                    <td><?php echo $member['telephone']; ?></td>
                    <td><button onclick="openModal(<?php echo $member['customerId']; ?>)" type="button" class="btn btn-warning"><i class="fas fa-edit"></i></button> <button onclick="openDelete(<?php echo $member['customerId']; ?>)" type="button" class="btn btn-danger"><i class="fas fa-trash-alt"></i></button>
                        <div class="modal-main" id="myDIV<?php echo $member['customerId']; ?>">
                            <div class="modal-content col-6">
                                <div class="row">
                                    <div class="col-12 ms-3 mt-3"><span style="font-size: 20px;">แก้ไข : <?php echo $member['title'] . $member['firstname'] . " " . $member['lastname']; ?></span></div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <hr>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 ms-3">
                                        <form name="editCustomer" method="post">
                                            <div class="row mb-2">
                                                <div class="col-11">
                                                    <div class="form-group">
                                                        <label for="title">ชื่อนำหน้า</label>
                                                        <input type="text" class="form-control" name="title" id="title<?php echo $member['customerId']; ?>" placeholder="นาย, นาง, นางสาว" value="<?php echo $member['title']; ?>">
                                                        <input type="hidden" class="form-control" name="customerId" id="customerId<?php echo $member['customerId']; ?>" value="<?php echo $member['customerId']; ?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mb-2">
                                                <div class="col-5">
                                                    <div class="form-group">
                                                        <label for="firstname">ชื่อ-นามสกุล</label>
                                                        <input type="text" class="form-control" name="firstname" placeholder="ชื่อ" id="firstname<?php echo $member['customerId']; ?>" value="<?php echo $member['firstname']; ?>">
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <label for="firstname">&nbsp;</label>
                                                        <input type="text" class="form-control" name="lastname" placeholder="นามสกุล" id="lastname<?php echo $member['customerId']; ?>" value="<?php echo $member['lastname']; ?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mb-2">
                                                <div class="col-11">
                                                    <div class="form-group">
                                                        <label for="idcard">รหัสบัตรประชาชน / Passport No.</label>
                                                        <input type="text" class="form-control" name="idcard" id="idcard<?php echo $member['customerId']; ?>" placeholder="รหัสบัตรประชาชน 13 หลัก" value="<?php echo $member['idcard']; ?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mb-2">
                                                <div class="col-11">
                                                    <div class="form-group">
                                                        <label for="telephone">เบอร์โทรศัพท์</label>
                                                        <input type="text" class="form-control" name="telephone" id="telephone<?php echo $member['customerId']; ?>" placeholder="08x-xxxxxxx" value="<?php echo $member['telephone']; ?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mb-2">
                                                <div class="col-11" style="text-align: right;">
                                                    <button onclick="onUpdate(false, $('#customerId<?php echo $member['customerId']; ?>').val(), $('#title<?php echo $member['customerId']; ?>').val(), $('#firstname<?php echo $member['customerId']; ?>').val(), $('#lastname<?php echo $member['customerId']; ?>').val(), $('#idcard<?php echo $member['customerId']; ?>').val(), $('#telephone<?php echo $member['customerId']; ?>').val())" name="submitAdd" class="btn btn-success" type="button" name="submitEdit" class="btn btn-success">Save</button>
                                                    <button onclick="openModal(<?php echo $member['customerId']; ?>)" type="button" class="btn btn-danger">Close</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
    <div class="modal-main" id="addMember">
        <div class="modal-content col-6">
            <div class="row">
                <div class="col-12 ms-3 mt-3"><span style="font-size: 20px;">เพิ่มสมาชิก</span></div>
            </div>
            <div class="row">
                <div class="col-12">
                    <hr>
                </div>
            </div>
            <div class="row">
                <div class="col-12 ms-3">
                    <form name="editCustomer" method="post">
                        <div class="row mb-2">
                            <div class="col-11">
                                <div class="form-group">
                                    <label for="title">ชื่อนำหน้า</label>
                                    <input type="text" class="form-control" name="title" id="title" placeholder="นาย, นาง, นางสาว" value="">
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-5">
                                <div class="form-group">
                                    <label for="firstname">ชื่อ-นามสกุล</label>
                                    <input type="text" class="form-control" name="firstname" placeholder="ชื่อ" id="firstname" value="">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="firstname">&nbsp;</label>
                                    <input type="text" class="form-control" name="lastname" placeholder="นามสกุล" id="lastname" value="">
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-11">
                                <div class="form-group">
                                    <label for="idcard">รหัสบัตรประชาชน / Passport No.</label>
                                    <input type="text" class="form-control" name="idcard" id="idcard" placeholder="รหัสบัตรประชาชน 13 หลัก" value="">
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-11">
                                <div class="form-group">
                                    <label for="telephone">เบอร์โทรศัพท์</label>
                                    <input type="text" class="form-control" name="telephone" id="telephone" placeholder="08x-xxxxxxx" value="">
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-11" style="text-align: right;">
                                <button type="submit" name="submitAdd" class="btn btn-success">Save</button>
                                <button type="button" onclick="addMember()" class="btn btn-danger">Close</button>
                            </div>
                        </div>
                    </form>
                    <?php
                    if (isset($_POST['submitAdd'])) {
                        $memberService->register(@$_POST['title'], @$_POST['firstname'], @$_POST['lastname'], @$_POST['idcard'], @$_POST['telephone']);
                    }

                    if (isset($_POST['submitEdit'])) {
                    }

                    ?>
                </div>
            </div>
        </div>
    </div>
</div>