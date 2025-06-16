<?php
namespace App\Http\Controllers;

use App\Models\Attribute;
use App\Models\AttributeValue;
use Illuminate\Http\Request;

class AttributeValueController extends Controller
{
    public function index()
    {
        $attributeValues = AttributeValue::with('attribute')->paginate(10);
        return view('Admin.AttributeValues.index', compact('attributeValues'));
    }

    public function create()
    {
        $attributes = Attribute::all();
        return view('Admin.AttributeValues.create', compact('attributes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'attribute_id' => 'required|exists:attributes,id',
            'value'        => 'required|unique:attribute_values,value',
            'slug'         => 'nullable|unique:attribute_values,slug',
        ]);

        $attributeValue = new AttributeValue([
            'attribute_id' => $request->input('attribute_id'),
            'value'        => $request->input('value'),
            'slug'        => $request->input('slug'),
        ]);
        $attributeValue->save();

        return redirect()
            ->route('admin.attributeValue.store')
            ->with('success', 'Thêm giá trị thuộc tính thành công!');
    }

    public function edit($id)
    {
        $attributeValue = AttributeValue::findOrFail($id);
        $attributes = Attribute::all();

        return view('Admin.AttributeValues.edit', compact('attributeValue', 'attributes'));
    }

    public function update(Request $request, $id)
    {
        $attributeValue = AttributeValue::findOrFail($id);

        $request->validate([
            'attribute_id' => 'required|exists:attributes,id',
            'value'        => 'required|unique:attribute_values,value,' . $id,
            'slug'         => 'nullable|unique:attribute_values,slug,' . $id,
        ]);

        $attributeValue->update([
            'attribute_id' => $request->input('attribute_id'),
            'value'        => $request->input('value'),
            'slug'        => $request->input('slug'),
        ]);

        return redirect()
            ->route('admin.attributeValue.index')
            ->with('success', 'Cập nhật giá trị thuộc tính thành công!');
    }

    public function destroy($id)
    {
        $attributeValue = AttributeValue::findOrFail($id);
        $attributeValue->delete();

        return redirect()
            ->route('admin.attributeValue.index')
            ->with('success', 'Xóa giá trị thuộc tính thành công!');
    }
}
