import React, {useEffect, useState} from 'react';
import {Breadcrumb, Button, Checkbox, Drawer, Form, Input, Popconfirm, Table} from 'antd';
import {DeleteOutlined, EditOutlined, HomeOutlined, PlusOutlined, UsergroupAddOutlined} from '@ant-design/icons';
import {Link} from "react-router-dom";

function useForceUpdate() {
    const [value, setValue] = useState(0); // integer state
    return () => setValue((value) => ++value); // update the state to force render
}

const columns = (setIsVisibleEditForm, setId, setName, setModules, setRoles) => {
    return [
        {
            title: 'Name',
            dataIndex: 'name',
            key: 'name',
            render: (text, record) => <a onClick={function () {
                setIsVisibleEditForm(true);
                axios.get('/lararole/api/role/' + record.id + '/edit').then((response) => {
                    setId(response.data.role.id);
                    setName(response.data.role.name);
                    setModules(response.data.role.modules);
                });
            }}>{text}</a>,
        },
        {
            title: 'Slug',
            dataIndex: 'slug',
            key: 'slug',
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
                    axios.get('/lararole/api/role/' + record.id + '/edit').then((response) => {
                        setId(response.data.role.id);
                        setName(response.data.role.name);
                        setModules(response.data.role.modules);
                    });
                }}>
                    <EditOutlined/> Edit
                    </a>
                    <Popconfirm
                        title="Are you sure delete this module?"
                        onConfirm={() => {
                            axios.delete('/lararole/api/role/' + record.id + '/delete').then(() => {
                                axios.get('/lararole/api/roles').then((response) => {
                                    setRoles(response.data.roles);
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

const moduleColumns = (modules, setModules) => {
    const forceUpdate = useForceUpdate();

    function getModule(modules, id) {
        let module = _.find(modules, {id: id});

        if (!module) {
            for (var i = 0; i < modules.length; i++) {
                if (modules[i].children) {
                    module = getModule(modules[i].children, id);
                    if (module) {
                        return module;
                    }
                }
            }
        }

        return module;
    }

    function updateModules(modules, module) {
        for (var i = 0; i < modules.length; i++) {
            if (modules[i].id === module.id) {
                modules[i] = module;
            } else {
                if (modules[i].children) {
                    updateModules(modules[i].children, module);
                }
            }
        }

        return modules;
    }

    function updateModule(module, readIndeterminate, writeIndeterminate, readChecked, writeChecked) {
        module.readIndeterminate = readIndeterminate;
        module.writeIndeterminate = writeIndeterminate;
        module.readChecked = readChecked;
        module.writeChecked = writeChecked;

        if (module.children) {
            for (var i = 0; i < module.children.length; i++) {
                updateModule(module.children[i], readIndeterminate, writeIndeterminate, readChecked, writeChecked)
            }
        }

        return module;
    }

    function updateParentModule(modules, module) {
        let parentModule = getModule(modules, module.module_id);

        let readSiblingsChecked = true;
        let writeSiblingsChecked = true;

        for (let i = 0; i < parentModule.children.length; i++) {
            if (parentModule.children[i].id !== module.id) {
                if (!parentModule.children[i].readChecked) {
                    readSiblingsChecked = false;
                }
            }
        }

        for (let i = 0; i < parentModule.children.length; i++) {
            if (parentModule.children[i].id !== module.id) {
                if (!parentModule.children[i].writeChecked) {
                    writeSiblingsChecked = false;
                }
            }
        }

        if (module.readChecked) {
            if (readSiblingsChecked) {
                parentModule.readChecked = true;
                parentModule.readIndeterminate = false;
            } else {
                parentModule.readChecked = false;
                parentModule.readIndeterminate = true;
            }
        } else if (module.readIndeterminate) {
            parentModule.readIndeterminate = true;
        } else {
            parentModule.readChecked = false;

            let siblingsChecked = false;

            for (let i = 0; i < parentModule.children.length; i++) {
                if (parentModule.children[i].id !== module.id) {
                    if (parentModule.children[i].readChecked) {
                        siblingsChecked = true;
                    }
                }
            }

            parentModule.readIndeterminate = siblingsChecked;
        }

        if (module.writeChecked) {
            if (writeSiblingsChecked) {
                parentModule.writeChecked = true;
                parentModule.writeIndeterminate = false;
            } else {
                parentModule.writeChecked = false;
                parentModule.writeIndeterminate = true;
            }
        } else if (module.writeIndeterminate) {
            parentModule.writeIndeterminate = true;
        } else {
            parentModule.writeChecked = false;

            let siblingsChecked = false;

            for (let i = 0; i < parentModule.children.length; i++) {
                if (parentModule.children[i].id !== module.id) {
                    if (parentModule.children[i].writeChecked) {
                        siblingsChecked = true;
                    }
                }
            }

            parentModule.writeIndeterminate = siblingsChecked;
        }

        let updatedModules = updateModules(modules, parentModule);

        if (parentModule.module_id) {
            updatedModules = updateParentModule(updatedModules, parentModule);
        }

        return updatedModules;
    }

    return [
        {
            title: 'Module',
            dataIndex: 'name',
            key: 'name',
        },
        {
            title: 'Permission',
            key: 'permission',
            render: (text, record) => (
                <span>
                    <Checkbox
                        onChange={e => {
                            let module = getModule(modules, record.id);
                            module = updateModule(module, false, false, !module.readChecked, false)
                            let updatedModules = updateModules(modules, module);

                            if (module.module_id) {
                                updatedModules = updateParentModule(modules, module)
                            }

                            setModules(updatedModules);
                            forceUpdate();
                        }}
                        indeterminate={record.readIndeterminate}
                        checked={record.readChecked}
                    >
                        Read
                    </Checkbox>

                    <Checkbox
                        onChange={e => {
                            let module = getModule(modules, record.id);
                            module = updateModule(module, false, false, false, !module.writeChecked)
                            let updatedModules = updateModules(modules, module);

                            if (module.module_id) {
                                updatedModules = updateParentModule(modules, module)
                            }

                            setModules(updatedModules);
                            forceUpdate();
                        }}
                        indeterminate={record.writeIndeterminate}
                        checked={record.writeChecked}
                    >
                        Read/Write
                    </Checkbox>
                </span>
            ),
        },

    ]
}

function Index() {
    const [roles, setRoles] = useState([]);
    const [selectedRoleIds, setSelectedRoleIds] = useState([]);

    const [id, setId] = useState(null);
    const [name, setName] = useState(null);
    const [modules, setModules] = useState([]);

    useEffect(() => {
        loadRoles();
    }, []);

    function loadRoles() {
        setSelectedRoleIds([]);
        axios.get('/lararole/api/roles').then((response) => {
            setRoles(response.data.roles);
        });
    }

    const rowSelection = {
        onChange: (selectedRowKeys, selectedRows) => {
            setSelectedRoleIds(selectedRows.map((selectedRow) => {
                return {
                    id: selectedRow.id
                };
            }));
        },
        onSelect: (record, selected, selectedRows) => {
            setSelectedRoleIds(selectedRows.map((selectedRow) => {
                return {
                    id: selectedRow.id
                };
            }));
        },
        onSelectAll: (selected, selectedRows, changeRows) => {
            setSelectedRoleIds(selectedRows.map((selectedRow) => {
                return {
                    id: selectedRow.id
                };
            }));
        },
    };

    const hasSelected = selectedRoleIds.length > 0;

    const [isVisibleCreateForm, setIsVisibleCreateForm] = useState(false);
    const [isVisibleEditForm, setIsVisibleEditForm] = useState(false);

    function showCreateForm() {
        axios.get('/lararole/api/modules').then((response) => {
            setModules(response.data.modules);
        });
        setIsVisibleCreateForm(true);
    }

    function closeCreateForm() {
        setIsVisibleCreateForm(false);
        resetRoleData()
    }

    function closeEditForm() {
        setIsVisibleEditForm(false);
        resetRoleData()
    }

    function resetRoleData() {
        setId(null);
        setName(null);
        setModules(null);
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
                    <Link to="/lararole/role">
                        <UsergroupAddOutlined/> Role
                    </Link>
                </Breadcrumb.Item>
            </Breadcrumb>
            <Popconfirm
                title="Are you sure delete this role?"
                onConfirm={() => {
                    axios.delete('/lararole/api/roles/delete', {
                        data: {roles: selectedRoleIds}
                    }).then(() => {
                        loadRoles();
                    });
                }}
            >
                <Button type="danger" disabled={!hasSelected}>
                    <DeleteOutlined/> Delete
                </Button>
            </Popconfirm>

            <Button type="primary" style={{marginBottom: 16, marginLeft: 8}} onClick={showCreateForm}>
                <PlusOutlined/> New Role
            </Button>

            <Drawer
                title="Create a new role"
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
                            axios.post('/lararole/api/role/create', {
                                name,
                                modules,
                            }).then(() => {
                                closeCreateForm();
                                loadRoles();
                            })
                        }} type="primary">
                            Create Role
                        </Button>
                    </div>
                }
            >
                <Form layout="vertical">
                    <Form.Item label="Role Name">
                        <Input placeholder="Manager, Editor etc..." value={name}
                               onChange={event => {
                                   setName(event.target.value)
                               }}/>
                    </Form.Item>

                    <Form.Item label="Modules">
                        <Table
                            columns={moduleColumns(modules, setModules)}
                            pagination={false}
                            dataSource={modules}/>
                    </Form.Item>
                </Form>
            </Drawer>

            <Drawer
                title="Update role"
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
                            axios.put('/lararole/api/role/' + id + '/update', {
                                name,
                                modules,
                            }).then((response) => {
                                closeEditForm();
                                loadRoles();
                            })
                        }} type="primary">
                            Update Role
                        </Button>
                    </div>
                }
            >
                <Form layout="vertical">
                    <Form.Item label="Module Name">
                        <Input placeholder="Product Management, Order Processing etc..." value={name}
                               onChange={event => {
                                   setName(event.target.value)
                               }}/>
                    </Form.Item>
                </Form>

            </Drawer>

            <span style={{marginLeft: 8}}>
                    {hasSelected ? `Selected ${selectedRoleIds.length} items` : ''}
                    </span>

            <Table
                columns={columns(setIsVisibleEditForm, setId, setName, setModules, setRoles)}
                rowSelection={rowSelection}
                dataSource={roles}/>
        </div>
    );
}

export default Index;
