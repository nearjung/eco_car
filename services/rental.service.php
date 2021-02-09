<?php
class RentalService
{
    public function get()
    {
        global $db, $api, $date;
        $cmd = " SELECT trrental.startDate,";
        $cmd .= " trrental.endDate,";
        $cmd .= " trrental.description,";
        $cmd .= " trrental.price,";
        $cmd .= " mscar.brand,";
        $cmd .= " mscar.model,";
        $cmd .= " mscar.plate,";
        $cmd .= " CONCAT(mscustomer.title,mscustomer.firstname, ' ', mscustomer.lastname) as fullName,";
        $cmd .= " mscustomer.telephone,";
        $cmd .= " mscustomer.idcard,";
        $cmd .= " trpicture.fileName,";
        $cmd .= " trpicture.filePath";
        $cmd .= " FROM trrental ";
        $cmd .= " LEFT JOIN mscar ON mscar.carId = trrental.carId";
        $cmd .= " LEFT JOIN mscustomer ON mscustomer.customerId = trrental.customerId";
        $cmd .= " LEFT JOIN trpicture ON trpicture.pictureId = mscar.carId";
        $cmd .= " WHERE trrental.active = 'Y'";
        $sql = $db->prepare($cmd);
        $sql->execute();
        $result = $sql->fetchAll();
        return $result;
    }
}
