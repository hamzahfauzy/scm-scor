<?= $this->extend('layouts/app') ?>

<?= $this->section('pageTitle') ?>
<?= $page_title ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card">
    <form action="" method="POST">
        <div class="card-body">
            <?= csrf_field() ?>
            
            <?= \App\Libraries\Form::generate($fields, $data) ?>

            <div style="margin-bottom:1rem;">
                <label>Produk</label><br>
                <div class="d-flex" style="gap:10px">
                    <select name="produk" id="produk" class="form-control">
                        <option value="">Pilih Produk</option>
                        <?php foreach($produk as $p): ?>
                        <option value="<?=$p['id']?>"><?=$p['nama']?></option>
                        <?php endforeach ?>
                    </select>

                    <input type="number" step=".1" name="jumlah" id="jumlah" class="form-control" placeholder="Jumlah">

                    <button type="button" class="btn btn-sm btn-primary" onclick="addItem()">Tambahkan</button>
                </div>

                <table id="tableItem" class="table table-bordered mt-4">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Jumlah</th>
                            <th></th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr><td colspan="3"><i>Tidak ada data</i></td></tr>
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="card-action">
            <button type="submit" class="btn btn-success">Submit</button>
            <a href="<?= site_url($page_slug . (isset($_GET['filter']) ? '?'.http_build_query($_GET) : ''))?>" class="btn btn-warning">Kembali</a>    
        </div>
    </form>
</div>
<script>
var items = []
function addItem()
{
    var produk = Array.from(document.querySelectorAll('#produk > option')).find(option => option.selected).text
    var item = {
        produk_id: document.querySelector('#produk').value,
        produk: produk,
        jumlah: document.querySelector('#jumlah').value,
    }

    // validation before push to item
    const index = items.findIndex(i => i.produk_id == item.produk_id)
    if(index > -1)
    {
        alert('Maaf! Produk sudah ada')
        return
    }

    if(item.produk_id == 0)
    {
        alert('Pilih produk terlebih dahulu')
        return
    }
    
    if(item.jumlah <= 0)
    {
        alert('Jumlah harus lebih dari 0')
        return
    }

    items.push(item)

    reloadItems()
}

function deleteItem(el)
{
    const index = items.findIndex(item => item.produk == el.dataset.produk)
    items.splice(index, 1)
    reloadItems()
}

function reloadItems()
{
    var table = document.querySelector('#tableItem')
    if(items.length == 0) {
        table.querySelector('tbody').innerHTML = '<tr><td colspan="3"><i>Tidak ada data</i></td></tr>'
        return 
    }

    table.querySelector('tbody').innerHTML = ''
    items.forEach((item, index) => {
        table.querySelector('tbody').innerHTML += `<tr>
            <td>${item.produk}</td>
            <td>${item.jumlah}</td>
            <td>
                <button type="button" class="btn btn-sm btn-danger" onclick="deleteItem(this)" data-produk="${item.produk_id}">Hapus</button>
                <input type="hidden" name="items[${index}][produk_id]" value="${item.produk_id}" class="produk">
                <input type="hidden" name="items[${index}][jumlah]" value="${item.jumlah}" class="jumlah">
            </td>
        </tr>`
    })
}
</script>
<?= $this->endSection() ?>