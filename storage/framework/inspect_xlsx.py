import zipfile
import re
import xml.etree.ElementTree as ET

path = r'202608_Report Kandidat Karyawan ISP.xlsx'
z = zipfile.ZipFile(path)
ns = {'a': 'http://schemas.openxmlformats.org/spreadsheetml/2006/main'}
shared_xml = z.read('xl/sharedStrings.xml').decode('utf-8')
shared = re.findall(r'<t[^>]*>(.*?)</t>', shared_xml)

for sheet in ['sheet1', 'sheet2', 'sheet3']:
    root = ET.fromstring(z.read(f'xl/worksheets/{sheet}.xml'))
    dimension = root.find('a:dimension', ns).attrib.get('ref', '')
    print(f'--- {sheet} dim={dimension} ---')
    for row in root.findall('.//a:row', ns)[:25]:
        values = []
        for cell in row.findall('a:c', ns):
            ref = cell.attrib.get('r', '')
            style = cell.attrib.get('s', '')
            typ = cell.attrib.get('t', '')
            v = cell.find('a:v', ns)
            f = cell.find('a:f', ns)
            value = ''
            if v is not None and v.text is not None:
                value = shared[int(v.text)] if typ == 's' else v.text
            elif f is not None and f.text is not None:
                value = '=' + f.text
            values.append(f'{ref}[s{style}]={value}')
        print(' | '.join(values))
    print()
