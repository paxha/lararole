import React, {useEffect, useState} from 'react';
import {Breadcrumb, Button, Col, Popconfirm, Row, Table} from 'antd';
import {DeleteOutlined, DeploymentUnitOutlined, EditOutlined, HomeOutlined, PlusOutlined} from '@ant-design/icons';

import {Link} from "react-router-dom";

const columns = [
    {
        title: 'Name',
        dataIndex: 'name',
        key: 'name',
        fixed: 'left',
        render: (text, record) => <Link to={{pathname: '/lararole/module/' + record.id + '/edit'}}>{text}</Link>,
    },
    {
        title: 'Slug',
        dataIndex: 'slug',
        key: 'slug',
    },
    {
        title: 'Alias',
        dataIndex: 'alias',
        key: 'alias',
    },
    {
        title: 'Last Update',
        dataIndex: 'updated_at',
        key: 'last_update',
    },
    {
        title: 'Created',
        dataIndex: 'created_at',
        key: 'created',
    },
    {
        title: '',
        key: 'action',
        fixed: 'right',
        render: (text, record) => (
            <span>
                <Link to={{pathname: '/lararole/module/' + record.id + '/create'}} style={{marginRight: 16}}>
                    <PlusOutlined/> New Child
                </Link>
                <Link to={{pathname: '/lararole/module/' + record.id + '/edit'}} style={{marginRight: 16}}>
                    <EditOutlined/> Edit
                </Link>
                <Popconfirm
                    title="Are you sure delete this module?"
                    onConfirm={() => {
                        axios.delete('/lararole/api/module/' + record.id + '/delete').then((response) => {
                            window.location = '/lararole/module';
                        })
                    }}
                >
                    <a>
                        <DeleteOutlined/> Delete
                    </a>
                </Popconfirm>
            </span>
        ),
    },
];

function Index() {
    const [modules, setModules] = useState([]);

    useEffect(() => {
        axios.get('/lararole/api/modules').then((response) => {
            setModules(response.data.modules);
        });
    }, []);

    const [selectedRowKeys, setSelectedRowKeys] = useState(0);
    const [selectedModuleIds, setSelectedModuleIds] = useState([]);

    function onSelectChange(selectedRowKeys) {
        setSelectedRowKeys(selectedRowKeys);
    }

    const rowSelection = {
        onChange: (selectedRowKeys, selectedRows) => {
            console.log(`selectedRowKeys: ${selectedRowKeys}`, 'selectedRows: ', selectedRows);
            onSelectChange(selectedRowKeys);
            setSelectedModuleIds(selectedRows.map((selectedRow) => {
                return {
                    id: selectedRow.id
                };
            }));
            console.log('selectedModuleIds', selectedModuleIds)
        },
        onSelect: (record, selected, selectedRows) => {
            console.log(record, selected, selectedRows);
            setSelectedModuleIds(selectedRows.map((selectedRow) => {
                return {
                    id: selectedRow.id
                };
            }));
            console.log('selectedModuleIds', selectedModuleIds)
        },
        onSelectAll: (selected, selectedRows, changeRows) => {
            console.log(selected, selectedRows, changeRows);
            setSelectedModuleIds(selectedRows.map((selectedRow) => {
                return {
                    id: selectedRow.id
                };
            }));
            console.log('selectedModuleIds', selectedModuleIds)
        },
    };

    const hasSelected = selectedRowKeys.length > 0;

    return (
        <div>
            <Breadcrumb style={{margin: '16px 0'}}>
                <Breadcrumb.Item>
                    <Link to="/lararole">
                        <HomeOutlined/> Home
                    </Link>
                </Breadcrumb.Item>
                <Breadcrumb.Item>
                    <Link to="/lararole/module">
                        <DeploymentUnitOutlined/> Module
                    </Link>
                </Breadcrumb.Item>
            </Breadcrumb>
            <Popconfirm
                title="Are you sure delete this module?"
                onConfirm={() => {
                    axios.delete('/lararole/api/modules/delete', {
                        data: {moduleIds: selectedModuleIds}
                    }).then((response) => {
                        window.location = '/lararole/module';
                    })
                }}
            >
                <Button type="danger" disabled={!hasSelected}>
                    <DeleteOutlined/> Delete
                </Button>
            </Popconfirm>

            <Button type="primary" style={{marginBottom: 16, marginLeft: 8}}>
                <Link to="/lararole/module/create">
                    <PlusOutlined/> New Module
                </Link>
            </Button>

            <span style={{marginLeft: 8}}>
                        {hasSelected ? `Selected ${selectedRowKeys.length} items` : ''}
                    </span>

            <Table columns={columns} rowSelection={rowSelection} dataSource={modules} scroll={{x: 1640}}/>
        </div>
    );
}

export default Index;
