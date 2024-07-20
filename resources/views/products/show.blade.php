<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <h1>
        List 
        <select name="" id="products" onchange="showProduct();">
            @foreach ($products as $item)
                <option value="{{$item->id}}">
                    {{$item->name}}
                </option>
            @endforeach
        </select>

        <br><br>

        name : <span id="productName"></span>
        <br>
        description : <span id="productDescription"></span>
        <br>
        price : <span id="productPrice"></span>
    </h1>



    <script>
        function showProduct(){
            productId = document.getElementById("products").value;
            fetch('http://127.0.0.1:8000/api/products/' + productId)
                .then(response => response.json())
                .then(data => {
                    document.getElementById("productName").innerText = data.product.name;
                    document.getElementById("productDescription").innerText = data.product.description;
                    document.getElementById("productPrice").innerText = data.product.price;
                })
                .catch(error => console.error('Error:', error));
        }
    </script>

</body>
</html>