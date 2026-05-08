
<?php 
 require_once ('includes/connect.php');
 if($_SERVER['REQUEST_METHOD'] == 'POST'){
    if(!empty($_POST['palya']) && !empty($_POST['helyszin']) && !empty($_POST['datum']) ) {
            $stmt = $dbh->prepare("INSERT INTO F1 (nev,helyszin,datum) VALUES (?, ?, ?)");
            $stmt->execute([$_POST['palya'], $_POST['helyszin'],$_POST['datum']]);
        }
 }
 $stmt = $dbh->query("SELECT * FROM F1");
    $gp = $stmt->fetchAll();



?>
<script src="https://unpkg.com/react@18/umd/react.development.js" crossorigin></script>
    <script src="https://unpkg.com/react-dom@18/umd/react-dom.development.js" crossorigin></script>
    <script src="https://unpkg.com/@babel/standalone/babel.min.js"></script>
<main id="root"></main>
<script type="text/babel">
    
    const { useState, useEffect } = React;
    const API_URL = '/logicals/api.php';

    function App() {
        const [gp, setGp] = useState([]);
        const betolt = () => {
        setGp(<?=json_encode($gp)?>); 
        };

        useEffect(() => { betolt(); }, []);     
        return (
            <>
            <div className="tablazat">
                <h2>Versenyek </h2>
                <form action="/crud" method="POST">
                <div>
                    <label htmlFor="palya">Verseny neve</label>
                    <input type="text" name="palya" required />
                </div>
                <div>
                    <label htmlFor="helyszin">Helyszín</label>
                    <input type="text" name="helyszin"  required />
                </div>
                <div>
                    <label htmlFor="datum">Dátum</label>
                    <input type="date" name="datum" required  />
                </div>
                <div>
                    
                    <button className="gomb" >Rögzítés</button>
                </div>
                </form>
            </div>

            <div className="tablazat">
                <table className="list" id="lista">
                    <thead>
                        <tr>
                            <th>Verseny neve</th>
                            <th>Helyszín</th>
                            <th>Időpont</th>
                            <th>Műveletek</th>
                        </tr>
                    </thead>
                    <tbody>
                        {gp.map((palya,idx) => (
                            <tr key={palya.azon}>
                                <td>{palya.nev}</td>
                                <td>{palya.helyszin}</td>
                                <td>{palya.datum}</td>
                                <td>
                                    <a href={`/crudmodosit?palya=${palya.nev}&helyszin=${palya.helyszin}&datum=${palya.datum}&azon=${palya.azon}`}><button className="gomb" >Módosítás</button></a>
                                    <form action="/crudtorles" method="POST">
                                    <input type="text" name="azon" value={palya.azon} readOnly hidden/>
                                    <button className="gomb">Törlés</button>
                                    </form>
                                </td>
                            </tr>
                        ))}
                    </tbody>
                </table>
            </div>
            </>
        );
    }

    const root = ReactDOM.createRoot(document.getElementById('root'));
    root.render(<App />);
</script>