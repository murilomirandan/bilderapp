<?php

class Bild
{
    private ?int $id;
    private string $namekuenstler;
    private string $namebild;
    private string $pfadbild;
    private float $preis;

    /**
     * @param int $id
     * @param string $namekuenstler
     * @param string $namebild
     * @param string $pfadbild
     * @param float $preis
     */
    public function __construct(string $namekuenstler, string $namebild, string $pfadbild, float $preis, ?int $id = null)
    {
        if (!is_null($id)){
            $this->id = $id;
        }
        $this->namekuenstler = $namekuenstler;
        $this->namebild = $namebild;
        $this->pfadbild = $pfadbild;
        $this->preis = $preis;
    }


    public function upload(): void
    {
        $mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DATABASE);

        $stmt = $mysqli->prepare("INSERT INTO basicinfo (id, namekuenstler, namebild, pfadbild, preis) VALUES (NULL, ?, ?, ?, ?);");

        $stmt->bind_param("ssss", $this->namekuenstler, $this->namebild, $this->pfadbild, $this->preis);
        $stmt->execute();
        $this->id = $stmt->insert_id;
    }

    public static function getAllAsObjects(): array
    {
        $mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DATABASE);
        $result = $mysqli->query("SELECT * FROM basicinfo");
        $bilder = [];

        while($row = mysqli_fetch_assoc($result)){
            $bilder[] = new Bild($row['namekuenstler'], $row['namebild'],
                $row['pfadbild'], $row['preis'], $row['id']);
        }
        $mysqli->close();

        return $bilder;
    }

    public function __toString(): string
    {
        return "Sie haben ein Bild von " . $this->namekuenstler . " hochgeladen. Der Name des Bildes ist '" .
            $this->namebild . "' und kostet " . $this->preis . " Euro.<br>" .
            "Intern info: (a) id: " . $this->id .
            " (b) pfad: " . $this->pfadbild . "<br>";
    }


}