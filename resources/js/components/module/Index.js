import React, {useEffect, useState} from 'react';
import {Breadcrumb, Button, Drawer, Form, Input, Popconfirm, Table, TreeSelect} from 'antd';
import {DeleteOutlined, DeploymentUnitOutlined, EditOutlined, HomeOutlined, PlusOutlined} from '@ant-design/icons';

import {Link} from "react-router-dom";

const columns = (setIsVisibleEditForm, setId, setName, setAlias, setIcon, setParentModuleId, setModules) => {
    return [
        {
            title: 'Name',
            width: 250,
            dataIndex: 'name',
            key: 'name',
            fixed: 'left',
            render: (text, record) => <a onClick={function () {
                setIsVisibleEditForm(true);
                axios.get('/lararole/api/module/' + record.id + '/edit').then((response) => {
                    setId(response.data.module.id);
                    setName(response.data.module.name);
                    setAlias(response.data.module.alias);
                    setIcon(response.data.module.icon);
                    if (response.data.module.parent) {
                        setParentModuleId(response.data.module.parent.id);
                    } else {
                        setParentModuleId(null);
                    }
                });
            }}>{text}</a>,
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
            width: 160,
            key: 'action',
            fixed: 'right',
            render: (text, record) => (
                <span>
                <a style={{marginRight: 16}} onClick={function () {
                    setIsVisibleEditForm(true);
                    axios.get('/lararole/api/module/' + record.id + '/edit').then((response) => {
                        setId(response.data.module.id);
                        setName(response.data.module.name);
                        setAlias(response.data.module.alias);
                        setIcon(response.data.module.icon);
                        if (response.data.module.parent) {
                            setParentModuleId(response.data.module.parent.id);
                        } else {
                            setParentModuleId(null);
                        }
                    });
                }}>
                    <EditOutlined/> Edit
                    </a>
                    <Popconfirm
                        title="Are you sure delete this module?"
                        onConfirm={() => {
                            axios.delete('/lararole/api/module/' + record.id + '/delete').then(() => {
                                axios.get('/lararole/api/modules').then((response) => {
                                    setModules(response.data.modules);
                                });
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
}

function Index() {
    const [modules, setModules] = useState([]);
    const [parentModuleId, setParentModuleId] = useState(null);
    const [selectedModuleIds, setSelectedModuleIds] = useState([]);

    const [id, setId] = useState(null);
    const [name, setName] = useState(null);
    const [alias, setAlias] = useState(null);
    const [icon, setIcon] = useState(null);

    useEffect(() => {
        loadModules();
    }, []);

    function loadModules() {
        setSelectedModuleIds([]);
        axios.get('/lararole/api/modules').then((response) => {
            setModules(response.data.modules);
        });
    }

    const rowSelection = {
        onChange: (selectedRowKeys, selectedRows) => {
            setSelectedModuleIds(selectedRows.map((selectedRow) => {
                return {
                    id: selectedRow.id
                };
            }));
        },
        onSelect: (record, selected, selectedRows) => {
            setSelectedModuleIds(selectedRows.map((selectedRow) => {
                return {
                    id: selectedRow.id
                };
            }));
        },
        onSelectAll: (selected, selectedRows, changeRows) => {
            setSelectedModuleIds(selectedRows.map((selectedRow) => {
                return {
                    id: selectedRow.id
                };
            }));
        },
    };

    const hasSelected = selectedModuleIds.length > 0;

    const [isVisibleCreateForm, setIsVisibleCreateForm] = useState(false);
    const [isVisibleEditForm, setIsVisibleEditForm] = useState(false);

    function showCreateForm() {
        setIsVisibleCreateForm(true);
    }

    function closeCreateForm() {
        setIsVisibleCreateForm(false);
        resetModuleData()
    }

    function closeEditForm() {
        setIsVisibleEditForm(false);
        resetModuleData()
    }

    function resetModuleData() {
        setId(null);
        setName(null);
        setAlias(null);
        setIcon(null);
        setParentModuleId(null);
    }

    function mapModulesTreeData() {
        return modules.map(module => {
            let object = {
                title: module.name,
                value: module.id,
                key: module.key,
            }

            if (module.children) {
                object.children = formatModuleTreeChildren(module.children)
            }

            return object;
        })
    }

    function formatModuleTreeChildren(modules) {
        return modules.map(module => {
            let object = {
                title: module.name,
                value: module.id,
                key: module.key,
            }

            if (module.children) {
                object.children = formatModuleTreeChildren(module.children)
            }

            return object
        })
    }

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
                    }).then(() => {
                        loadModules();
                    });
                }}
            >
                <Button type="danger" disabled={!hasSelected}>
                    <DeleteOutlined/> Delete
                </Button>
            </Popconfirm>

            <Button type="primary" style={{marginBottom: 16, marginLeft: 8}} onClick={showCreateForm}>
                <PlusOutlined/> New Module
            </Button>

            <Drawer
                title="Create a new module"
                width={720}
                onClose={closeCreateForm}
                visible={isVisibleCreateForm}
                bodyStyle={{paddingBottom: 80}}
                footer={
                    <div
                        style={{
                            textAlign: 'right',
                        }}
                    >
                        <Button
                            onClick={closeCreateForm}
                            style={{marginRight: 8}}
                        >
                            Cancel
                        </Button>
                        <Button onClick={() => {
                            axios.post('/lararole/api/module/create', {
                                module_id: parentModuleId,
                                name,
                                alias,
                                icon
                            }).then(() => {
                                closeCreateForm();
                                loadModules();
                            })
                        }} type="primary">
                            Create Module
                        </Button>
                    </div>
                }
            >
                <Form layout="vertical">
                    <Form.Item label="Choose Parent Module">
                        <TreeSelect
                            style={{width: '100%'}}
                            value={parentModuleId}
                            dropdownStyle={{maxHeight: 400, overflow: 'auto'}}
                            treeData={mapModulesTreeData()}
                            placeholder="Please select"
                            onChange={(value) => {
                                setParentModuleId(value);
                            }}
                        />
                    </Form.Item>

                    <Form.Item label="Module Name">
                        <Input placeholder="Product Management, Order Processing etc..." value={name}
                               onChange={event => {
                                   setName(event.target.value)
                               }}/>
                    </Form.Item>

                    <Form.Item
                        label="Alias"
                    >
                        <Input placeholder="Product Management, Order Processing etc..." value={alias}
                               onChange={event => {
                                   setAlias(event.target.value)
                               }}/>
                    </Form.Item>

                    <Form.Item
                        label="Icon">
                        <Input placeholder="fa fa-users etc..." value={icon} onChange={event => {
                            setIcon(event.target.value)
                        }}/>
                    </Form.Item>
                </Form>
            </Drawer>

            <Drawer
                title="Update module"
                width={720}
                onClose={closeEditForm}
                visible={isVisibleEditForm}
                bodyStyle={{paddingBottom: 80}}
                footer={
                    <div
                        style={{
                            textAlign: 'right',
                        }}
                    >
                        <Button
                            onClick={closeEditForm}
                            style={{marginRight: 8}}
                        >
                            Cancel
                        </Button>
                        <Button onClick={() => {
                            axios.put('/lararole/api/module/' + id + '/update', {
                                module_id: parentModuleId,
                                name,
                                alias,
                                icon
                            }).then((response) => {
                                closeEditForm();
                                loadModules();
                            })
                        }} type="primary">
                            Update Module
                        </Button>
                    </div>
                }
            >
                <Form layout="vertical">
                    <Form.Item label="Choose Parent Module">
                        <TreeSelect
                            style={{width: '100%'}}
                            value={parentModuleId}
                            dropdownStyle={{maxHeight: 400, overflow: 'auto'}}
                            treeData={mapModulesTreeData()}
                            placeholder="Please select"
                            onChange={(value) => {
                                setParentModuleId(value);
                            }}
                            allowClear
                        />
                    </Form.Item>

                    <Form.Item label="Module Name">
                        <Input placeholder="Product Management, Order Processing etc..." value={name}
                               onChange={event => {
                                   setName(event.target.value)
                               }}/>
                    </Form.Item>

                    <Form.Item
                        label="Alias"
                    >
                        <Input placeholder="Product Management, Order Processing etc..." value={alias}
                               onChange={event => {
                                   setAlias(event.target.value)
                               }}/>
                    </Form.Item>

                    <Form.Item
                        label="Icon">
                        <Input placeholder="fa fa-users etc..." value={icon} onChange={event => {
                            setIcon(event.target.value)
                        }}/>
                    </Form.Item>
                </Form>
            </Drawer>

            <span style={{marginLeft: 8}}>
                    {hasSelected ? `Selected ${selectedModuleIds.length} items` : ''}
                    </span>

            <Table
                columns={columns(setIsVisibleEditForm, setId, setName, setAlias, setIcon, setParentModuleId, setModules)}
                rowSelection={rowSelection}
                dataSource={modules}
                scroll={{x: 1200}}/>
        </div>
    );
}

export default Index;
