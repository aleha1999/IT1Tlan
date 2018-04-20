<!DOCTYPE html5>
<html>
  <head>
    <link rel="stylesheet" href="/css/materialize.css"/>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700"/>
    <link rel="stylesheet" href="/css/flag-icon.min.css"/>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons"/>
    <link rel="stylesheet" href="/css/default.css"/>
    <link rel="stylesheet" href="/css/nouislider.css"/>
    <script src="/js/jquery.min.js"></script>
    <script src="/js/materialize.min.js"></script>
    <script src="/js/nouislider.min.js"></script>
    <script src="/js/main.js"></script>
    <script src="/js/tlanapi.js"></script>
    <script src="/js/players.js"></script>
  </head>
  <body>
    <nav>
      <div class="nav-wrapper"><a class="brand-logo" href="/" style="margin-left:10px">TromsøLAN</a>
        <ul class="right">
          <li><a href="/teams/">Lag</a></li>
          <li><a href="/players/">Spillere</a></li>
          <li><a href="/competitions/">Konkurranser</a></li>
          <li><a href="/games/">Spill</a></li>
        </ul>
      </div>
    </nav>
    <div class="container">
      <h1>Spillerdatabase</h1>
      <div class="fixed-action-btn"><a class="modal-trigger btn-floating btn-large waves-effect waves-light" href="#add_player_modal"><i class="material-icons">add</i></a></div>
      <table class="striped">
        <tr class="head">
          <th>Fornavn</th>
          <th>Etternavn</th>
          <th>E-post</th>
          <th>Gamertag</th>
          <th>Nasjonalitet</th>
          <th>Handlinger</th>
        </tr>
        <tr id="player_proto">
          <td>Firstname</td>
          <td>Surname</td>
          <td>EMAIL</td>
          <td>GAMERTAG</td>
          <td> <i class="flag-icon flag-icon-no" title="Norge"></i></td>
          <td><i class="material-icons red-text">delete</i><span class="btnsep"></span><i class="material-icons orange-text">edit</i><span class="btnsep"></span><i class="material-icons blue-text">info</i></td>
        </tr>
      </table>
      <div class="modal no-autoinit" id="add_player_modal">
        <div class="modal-content">
          <h4>Legg til/rediger en spiller</h4>
          <form id="add_player_form">
            <h5>Navn</h5>
            <div class="row">
              <div class="col s6 input-field">
                <input type="text" name="firstname" required="required" id="fname"/>
                <label for="fname">Fornavn</label>
              </div>
              <div class="col s6 input-field">
                <input type="text" name="surname" required="required" id="sname"/>
                <label for="sname">Etternavn</label>
              </div>
            </div>
            <h5>Spillerinformasjon</h5>
            <div class="row">
              <div class="col s6 input-field">
                <input type="text" name="tag" required="required" id="gtag"/>
                <label for="gtag">Gamertag</label>
              </div>
              <div class="col s6 input-field">
                <input class="autocomplete" name="nationality" type="text" id="nat"/>
                <label for="nat">Nasjonalitet</label>
              </div>
            </div>
            <h5>Adresse</h5>
            <div class="row">
              <div class="col s9 input-field">
                <input type="text" name="address" id="address"/>
                <label for="address">Adresse</label>
              </div>
              <div class="col s3 input-field">
                <input type="number" name="postnumber" id="pnumber"/>
                <label for="pnumber">Postnummer</label>
              </div>
            </div>
            <h5>Kontaktinformasjon</h5>
            <div class="row">
              <div class="col s6 input-field"><i class="material-icons prefix">phone</i>
                <input type="number" name="phone" required="required" id="phone"/>
                <label for="phone">Telefonnummer</label>
              </div>
              <div class="col s6 input-field"><i class="material-icons prefix">mail</i>
                <input type="email" name="email" required="required" id="email"/>
                <label for="email">E-post</label>
              </div>
            </div>
            <h5>Spillerrating</h5>
            <h6>0-100</h6>
            <div class="row">
              <p class="range-field">
                <input type="range" min="0" max="100" name="rating" id="playerratingrange"/>
              </p>
            </div>
          </form>
          <div class="modal-footer"><a class="modal-action modal-close waves-effect waves-red btn-flat red-text">Avbryt</a>
            <button class="btn-flat waves-effect" id="add_player_save">Lagre</button>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>