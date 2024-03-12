<div>
    <table>
       <thead>
       <tr>
            <th width="1%">#</th>
            <th>Warehouse Code</th>
            <th>Name</th>
            <th>SKU Code</th>
            <th>Wholesale Price</th>
            <th>Distributor Price</th>
            <th>Retail Price</th>
            <th>Current Stock</th>
            <th>Date</th>
            <th>time</th>
       </tr>
       </thead>
       <tbody>
       @foreach ($products as $key=>$product)
          <tr>
            <td>{{ $key + 1 }}</td>
            <td>{{ $product->warehouse_code }}</td>
            <td>{{ $product->product_name }}</td>
            <td>{{ $product->sku_code }}</td>

            @if ($product->ProductPrice->buying_price ==  0 || 00)
                <td>{{'Price Not set' }}</td>
            @else
                <td>{{number_format((float) $product->ProductPrice->buying_price)}}</td>
            @endif
            <td>
                {{ number_format((float) $product->ProductPrice()->pluck('distributor_price')->implode('')) }}
            </td>
            <td>
                {{ number_format((float) $product->ProductPrice()->pluck('selling_price')->implode('')) }}
            </td>
            <td>{{ $product->Inventory()->pluck('current_stock')->implode('') }} </td>
            <td>{{ $product->updated_at->format('d/m/Y') }}</td>
            <td>{{ $product->updated_at->format('H:i:s') }}</td>
          </tr>
       @endforeach
       </tbody>
    </table>
 </div>
 