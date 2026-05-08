<div className="tablazat">
    <h2>Versenyek </h2>
    <form action="/crudmodosit" method="POST">
        <input type="text" name="azon" value="<?=$_GET['azon']?>" readonly hidden />
    <div>
        
        <label htmlFor="palya">Verseny neve</label>
        <input type="text" name="palya" value="<?=$_GET['palya']?>" required />
    </div>
    <div>
        <label htmlFor="helyszin">Helyszín</label>
        <input type="text" name="helyszin" value="<?=$_GET['helyszin']?>"  required />
    </div>
    <div>
        <label htmlFor="datum">Dátum</label>
        <input type="date" name="datum" required value="<?=$_GET['datum']?>" />
    </div>
    <div>
        
        <button className="gomb" >Módosítás</button>
        <a href="/crud"><button className="gomb" type="button" >Mégsem</button></a>
    </div>
    </form>
</div>
