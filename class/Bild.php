<?php

class Bild
{
    private ?int $id;
    private string $namekuenstler;
    private string $namebild;
    private string $pfadbild;
    private float $preis;
    private ?string $bild;

    /**
     * @param int $id
     * @param string $namekuenstler
     * @param string $namebild
     * @param string $pfadbild
     * @param float $preis
     */
    public function __construct(string $namekuenstler, string $namebild, string $pfadbild,
                                float $preis, ?string $bild = null, ?int $id = null)
    {
        if (!is_null($id)){
            $this->id = $id;
        }

        if (!is_null($bild)){
            $this->bild = $bild;
        }

        $this->namekuenstler = $namekuenstler;
        $this->namebild = $namebild;
        $this->pfadbild = $pfadbild;
        $this->preis = $preis;
    }

    public function upload(): void
    {
        $mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DATABASE);

        $stmt = $mysqli->prepare("INSERT INTO basicinfo (id, namekuenstler, namebild, pfadbild, preis, bild) VALUES (NULL, ?, ?, ?, ?, ?)");

        $stmt->bind_param("sssds", $this->namekuenstler, $this->namebild, $this->pfadbild, $this->preis, $this->bild);
        $stmt->execute();
        $this->id = $stmt->insert_id;
    }

    public static function accessImage(int $id): ?array
    {
        $mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DATABASE);

        $stmt = $mysqli->prepare("SELECT * FROM basicinfo WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();

        $result = $stmt->get_result();
        if($row = $result->fetch_assoc()){
            return $row;
        }
        return null;
    }

    public static function getAllAsObjects(): array
    {
        $mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASSWD, DATABASE);
        $result = $mysqli->query("SELECT * FROM basicinfo");
        $bilder = [];

        while($row = mysqli_fetch_assoc($result)){
            $bilder[] = new Bild($row['namekuenstler'], $row['namebild'],
                $row['pfadbild'], $row['preis'], null, $row['id']);
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