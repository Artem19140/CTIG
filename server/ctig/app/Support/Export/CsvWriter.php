<?php

namespace App\Support\Export;

class CsvWriter{
   protected $handle;
   public function __construct(
    string $handle = 'php://output'
   ){
        $this->handle = fopen($handle, 'w');

        if ($this->handle === false) {
            throw new \RuntimeException("Cannot open CSV stream");
        }
   }

    public function setHeaders(array $headers){
     fputcsv($this->handle, $headers);
    }

    public function writeRow(array $data){
        fputcsv($this->handle,$data);
    }

    public function close(){
        if (\is_resource($this->handle)) {
            fclose($this->handle);
        }
    }

    public function writeBom():void{
        fwrite($this->handle, "\xEF\xBB\xBF");
    }

    public function __destruct(){
        $this->close();
    }
}