<?php

function vypis_aktuality(){
    $db = napoj_db();
    $sql =<<<EOF
      SELECT * FROM Aktuality ORDER BY date(datum) DESC;
EOF;
    $ret = $db->query($sql);
    echo '<div class="aktualityPage">';
    while($row = $ret->fetchArray(SQLITE3_ASSOC) ){
      echo '<div class="card">';
        echo '<h5 class="card-header">'.$row['nadpis'].'</h5>';
        echo '<div class="card-body">';
          echo '<p class="card-text">'.$row['text'].'</p>';
          echo '<input type="hidden" name="akt_id" value="'.$row['id'].'">';
        echo '</div>';
        echo '<div class="card-footer">'.vypisDatum($row['datum']).'</div>';
      echo '</div>';
    } 
    $db->close();
  }

function vypis_aktuality_admin(){
    $db = napoj_db();
    $sql =<<<EOF
      SELECT * FROM Aktuality ORDER BY date(datum) DESC;
EOF;
    $ret = $db->query($sql);
    echo '<div class="aktualityPage">';
    while($row = $ret->fetchArray(SQLITE3_ASSOC) ){
      echo '<div class="card">';
      echo '<h5 class="card-header">'.$row['nadpis'].'</h5>';
      echo '<div class="card-body">';
      echo '<p class="card-text">'.$row['text'].'</p>';
      echo '<input type="hidden" name="akt_id" value="'.$row['id'].'">';
      echo '</div>';
      echo '<div class="card-footer">';
      echo '<form novalidate method="post">';
      echo '<input type="submit" name="vymaz_akt" class="btn btn-admin" value="Vymaž" style="margin-right:10px;">';
      echo '<input type="submit" name="uprav_akt" class="btn btn-admin" value="Uprav" style="margin-right:10px;">';
      echo vypisDatum($row['datum']);
      echo '</form>';
      echo '</div>';
      echo '</div>';
    } 
    $db->close();
}

function aktualityPagination(){?>
  <ul class="pagination justify-content-center">
    <li class="page-item">
      <a class="page-link" href="#" aria-label="Previous">
        <span aria-hidden="true">&laquo;</span>
        <span class="sr-only">Previous</span>
      </a>
    </li>
    <li class="page-item">
      <a class="page-link" href="#">1</a>
    </li>
    <li class="page-item">
      <a class="page-link" href="#">2</a>
    </li>
    <li class="page-item">
      <a class="page-link" href="#">3</a>
    </li>
    <li class="page-item">
      <a class="page-link" href="#" aria-label="Next">
        <span aria-hidden="true">&raquo;</span>
        <span class="sr-only">Next</span>
      </a>
    </li>
  </ul>
<?php
}

