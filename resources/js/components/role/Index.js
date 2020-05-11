import React, { useEffect, useState } from 'react'
import { Breadcrumb, Button, Checkbox, Drawer, Form, Input, notification, Popconfirm, Table, Tag } from 'antd'
import { DeleteOutlined, EditOutlined, HomeOutlined, PlusOutlined, UsergroupAddOutlined } from '@ant-design/icons'
import { Link } from 'react-router-dom'

function useForceUpdate () {
  const [value, setValue] = useState(0) // integer state
  return () => setValue((value) => ++value) // update the state to force render
}

const columns = (setIsVisibleEditForm, setId, setName, setModules, setRoles, openNotification) => {
  const forceUpdate = useForceUpdate()

  function getModule (modules, id) {
    let module = _.find(modules, { id: id })

    if (!module) {
      for (var i = 0; i < modules.length; i++) {
        if (modules[i].children) {
          module = getModule(modules[i].children, id)
          if (module) {
            return module
          }
        }
      }
    }

    return module
  }

  function updateModules (modules, module) {
    for (var i = 0; i < modules.length; i++) {
      if (modules[i].id === module.id) {
        modules[i] = module
      }
    }

    return modules
  }

  function updateModule (module, readIndeterminate, writeIndeterminate, readChecked, writeChecked) {
    module.readIndeterminate = readIndeterminate
    module.writeIndeterminate = writeIndeterminate
    module.readChecked = readChecked
    module.writeChecked = writeChecked

    return module
  }

  function updateParentModule (modules, module) {
    const parentModule = getModule(modules, module.module_id)

    const hasSiblings = parentModule.children ? parentModule.children.length > 1 : false

    const readChecked = module.readChecked
    const writeChecked = module.writeChecked

    const readIndeterminate = module.readIndeterminate
    const writeIndeterminate = module.writeIndeterminate

    let readSiblingsChecked = false
    let writeSiblingsChecked = false

    let readSiblingsIndeterminate = false
    let writeSiblingsIndeterminate = false

    if (hasSiblings) {
      for (let i = 0; i < parentModule.children.length; i++) {
        if (parentModule.children[i].id !== module.id) {
          if (parentModule.children[i].readChecked) {
            readSiblingsChecked = true
          }
        }
      }

      for (let i = 0; i < parentModule.children.length; i++) {
        if (parentModule.children[i].id !== module.id) {
          if (parentModule.children[i].writeChecked) {
            writeSiblingsChecked = true
          }
        }
      }

      for (let i = 0; i < parentModule.children.length; i++) {
        if (parentModule.children[i].id !== module.id) {
          if (parentModule.children[i].readIndeterminate) {
            readSiblingsIndeterminate = true
          }
        }
      }

      for (let i = 0; i < parentModule.children.length; i++) {
        if (parentModule.children[i].id !== module.id) {
          if (parentModule.children[i].writeIndeterminate) {
            writeSiblingsIndeterminate = true
          }
        }
      }
    }

    if (!hasSiblings) {
      parentModule.readChecked = readChecked

      parentModule.readIndeterminate = readIndeterminate

      parentModule.writeChecked = writeChecked
      parentModule.writeIndeterminate = writeIndeterminate
    } else {
      if (readChecked && readSiblingsChecked) {
        parentModule.readChecked = true
        parentModule.readIndeterminate = false
      } else if (readChecked && !readSiblingsChecked) {
        parentModule.readChecked = false
        parentModule.readIndeterminate = true
      } else if (!readChecked && readSiblingsChecked) {
        parentModule.readChecked = false
        parentModule.readIndeterminate = true
      }

      if (!readChecked && !readSiblingsChecked) {
        parentModule.readChecked = false
        parentModule.readIndeterminate = false
      }

      if (readIndeterminate || readSiblingsIndeterminate) {
        parentModule.readChecked = false
        parentModule.readIndeterminate = true
      }

      if (writeChecked && writeSiblingsChecked) {
        parentModule.writeChecked = true
        parentModule.writeIndeterminate = false
      } else if (writeChecked && !writeSiblingsChecked) {
        parentModule.writeChecked = false
        parentModule.writeIndeterminate = true
      } else if (!writeChecked && writeSiblingsChecked) {
        parentModule.writeChecked = false
        parentModule.writeIndeterminate = true
      }

      if (!writeChecked && !writeSiblingsChecked) {
        parentModule.writeChecked = false
        parentModule.writeIndeterminate = false
      }

      if (writeIndeterminate || writeSiblingsIndeterminate) {
        parentModule.writeChecked = false
        parentModule.writeIndeterminate = true
      }
    }

    let updatedModules = updateModules(modules, parentModule)

    if (parentModule.module_id) {
      updatedModules = updateParentModule(updatedModules, parentModule)
    }

    return updatedModules
  }

  return [
    {
      title: 'Name',
      dataIndex: 'name',
      key: 'name',
      render: (text, record) => <a onClick={function () {
        let roleModules = []
        let modules = []

        axios.get('/lararole/api/role/' + record.id + '/edit')
          .then(response => {
            setId(response.data.role.id)
            setName(response.data.role.name)
            roleModules = response.data.role.modules
          })
          .catch(error => {
            openNotification(error.response.data.message, error.response.data.description, 'error')
          })

        axios.get('/lararole/api/modules')
          .then(response => {
            modules = response.data.modules

            for (let i = 0; i < roleModules.length; i++) {
              const roleModule = getModule(modules, roleModules[i].id)

              const updatedRoleModule = updateModule(roleModule, false, false, roleModules[i].permission === 'read', roleModules[i].permission === 'write')

              let updatedModules = updateModules(modules, updatedRoleModule)

              if (updatedRoleModule.module_id) {
                updatedModules = updateParentModule(updatedModules, updatedRoleModule)
              }
              setModules(updatedModules)
              forceUpdate()
            }

            setModules(response.data.modules)
          })
          .catch(error => {
            openNotification(error.response.data.message, error.response.data.description, 'error')
          })
        setIsVisibleEditForm(true)
      }}>{text}</a>
    },
    {
      title: 'Slug',
      dataIndex: 'slug',
      key: 'slug'
    },
    {
      title: 'Modules',
      width: 300,
      dataIndex: 'modules',
      key: 'modules',
      render: modules => (
        <>
          {
            modules.map(module => {
              const color = !module.module_id ? (module.permission === 'write' ? 'geekblue' : 'blue') : 'cyan'

              return (
                <Tag color={color} key={module.slug} style={{ marginTop: 5 }}>
                  {module.name}
                </Tag>
              )
            })
          }
        </>
      )
    },
    {
      title: 'Last Update',
      width: 130,
      dataIndex: 'updated_at',
      key: 'last_update'
    },
    {
      title: 'Created',
      width: 130,
      dataIndex: 'created_at',
      key: 'created'
    },
    {
      title: '',
      width: 160,
      key: 'action',
      fixed: 'right',
      render: (text, record) => (
        <span>
          <a style={{ marginRight: 16 }} onClick={function () {
            let roleModules = []
            let modules = []

            axios.get('/lararole/api/role/' + record.id + '/edit')
              .then(response => {
                setId(response.data.role.id)
                setName(response.data.role.name)
                roleModules = response.data.role.modules
              })
              .catch(error => {
                openNotification(error.response.data.message, error.response.data.description, 'error')
              })

            axios.get('/lararole/api/modules')
              .then(response => {
                modules = response.data.modules

                for (let i = 0; i < roleModules.length; i++) {
                  const roleModule = getModule(modules, roleModules[i].id)

                  const updatedRoleModule = updateModule(roleModule, false, false, roleModules[i].permission === 'read', roleModules[i].permission === 'write')

                  let updatedModules = updateModules(modules, updatedRoleModule)

                  if (updatedRoleModule.module_id) {
                    updatedModules = updateParentModule(updatedModules, updatedRoleModule)
                  }
                  setModules(updatedModules)
                  forceUpdate()
                }

                setModules(response.data.modules)
              })
              .catch(error => {
                openNotification(error.response.data.message, error.response.data.description, 'error')
              })
            setIsVisibleEditForm(true)
          }}>
            <EditOutlined/> Edit
          </a>
          <Popconfirm
            title="Are you sure delete this module?"
            onConfirm={() => {
              axios.delete('/lararole/api/role/' + record.id + '/delete')
                .then(response => {
                  openNotification(response.data.message, response.data.description)
                  axios.get('/lararole/api/roles')
                    .then(rolesResponse => {
                      setRoles(rolesResponse.data.roles)
                    }).catch(rolesError => {
                      openNotification(rolesError.response.data.message, rolesError.response.data.description, 'error')
                    })
                })
                .catch(error => {
                  openNotification(error.response.data.message, error.response.data.description, 'error')
                })
            }}
          >
            <a>
              <DeleteOutlined/> Delete
            </a>
          </Popconfirm>
        </span>
      )
    }
  ]
}

const moduleColumns = (modules, setModules) => {
  const forceUpdate = useForceUpdate()

  function getModule (modules, id) {
    let module = _.find(modules, { id: id })

    if (!module) {
      for (var i = 0; i < modules.length; i++) {
        if (modules[i].children) {
          module = getModule(modules[i].children, id)
          if (module) {
            return module
          }
        }
      }
    }

    return module
  }

  function updateModules (modules, module) {
    for (var i = 0; i < modules.length; i++) {
      if (modules[i].id === module.id) {
        modules[i] = module
      } else {
        if (modules[i].children) {
          updateModules(modules[i].children, module)
        }
      }
    }

    return modules
  }

  function updateModule (module, readIndeterminate, writeIndeterminate, readChecked, writeChecked) {
    module.readIndeterminate = readIndeterminate
    module.writeIndeterminate = writeIndeterminate
    module.readChecked = readChecked
    module.writeChecked = writeChecked

    if (module.children) {
      for (var i = 0; i < module.children.length; i++) {
        updateModule(module.children[i], readIndeterminate, writeIndeterminate, readChecked, writeChecked)
      }
    }

    return module
  }

  function updateParentModule (modules, module) {
    const parentModule = getModule(modules, module.module_id)

    const hasSiblings = parentModule.children ? parentModule.children.length > 1 : false

    const readChecked = module.readChecked
    const writeChecked = module.writeChecked

    const readIndeterminate = module.readIndeterminate
    const writeIndeterminate = module.writeIndeterminate

    let readSiblingsChecked = false
    let writeSiblingsChecked = false

    let readSiblingsIndeterminate = false
    let writeSiblingsIndeterminate = false

    if (hasSiblings) {
      for (let i = 0; i < parentModule.children.length; i++) {
        if (parentModule.children[i].id !== module.id) {
          if (parentModule.children[i].readChecked) {
            readSiblingsChecked = true
          }
        }
      }

      for (let i = 0; i < parentModule.children.length; i++) {
        if (parentModule.children[i].id !== module.id) {
          if (parentModule.children[i].writeChecked) {
            writeSiblingsChecked = true
          }
        }
      }

      for (let i = 0; i < parentModule.children.length; i++) {
        if (parentModule.children[i].id !== module.id) {
          if (parentModule.children[i].readIndeterminate) {
            readSiblingsIndeterminate = true
          }
        }
      }

      for (let i = 0; i < parentModule.children.length; i++) {
        if (parentModule.children[i].id !== module.id) {
          if (parentModule.children[i].writeIndeterminate) {
            writeSiblingsIndeterminate = true
          }
        }
      }
    }

    if (!hasSiblings) {
      parentModule.readChecked = readChecked

      parentModule.readIndeterminate = readIndeterminate

      parentModule.writeChecked = writeChecked
      parentModule.writeIndeterminate = writeIndeterminate
    } else {
      if (readChecked && readSiblingsChecked) {
        parentModule.readChecked = true
        parentModule.readIndeterminate = false
      } else if (readChecked && !readSiblingsChecked) {
        parentModule.readChecked = false
        parentModule.readIndeterminate = true
      } else if (!readChecked && readSiblingsChecked) {
        parentModule.readChecked = false
        parentModule.readIndeterminate = true
      }

      if (!readChecked && !readSiblingsChecked) {
        parentModule.readChecked = false
        parentModule.readIndeterminate = false
      }

      if (readIndeterminate || readSiblingsIndeterminate) {
        parentModule.readChecked = false
        parentModule.readIndeterminate = true
      }

      if (writeChecked && writeSiblingsChecked) {
        parentModule.writeChecked = true
        parentModule.writeIndeterminate = false
      } else if (writeChecked && !writeSiblingsChecked) {
        parentModule.writeChecked = false
        parentModule.writeIndeterminate = true
      } else if (!writeChecked && writeSiblingsChecked) {
        parentModule.writeChecked = false
        parentModule.writeIndeterminate = true
      }

      if (!writeChecked && !writeSiblingsChecked) {
        parentModule.writeChecked = false
        parentModule.writeIndeterminate = false
      }

      if (writeIndeterminate || writeSiblingsIndeterminate) {
        parentModule.writeChecked = false
        parentModule.writeIndeterminate = true
      }
    }

    let updatedModules = updateModules(modules, parentModule)

    if (parentModule.module_id) {
      updatedModules = updateParentModule(updatedModules, parentModule)
    }

    return updatedModules
  }

  return [
    {
      title: 'Module',
      dataIndex: 'name',
      key: 'name'
    },
    {
      title: 'Permission',
      key: 'permission',
      render: (text, record) => (
        <span>
          <Checkbox
            onChange={() => {
              let module = getModule(modules, record.id)
              module = updateModule(module, false, false, !module.readChecked, false)
              let updatedModules = updateModules(modules, module)

              if (module.module_id) {
                updatedModules = updateParentModule(modules, module)
              }

              setModules(updatedModules)
              forceUpdate()
            }}
            indeterminate={record.readIndeterminate}
            checked={record.readChecked}
          >
                    Read
          </Checkbox>

          <Checkbox
            onChange={() => {
              let module = getModule(modules, record.id)
              module = updateModule(module, false, false, false, !module.writeChecked)
              let updatedModules = updateModules(modules, module)

              if (module.module_id) {
                updatedModules = updateParentModule(modules, module)
              }

              setModules(updatedModules)
              forceUpdate()
            }}
            indeterminate={record.writeIndeterminate}
            checked={record.writeChecked}
          >
                    Read/Write
          </Checkbox>
        </span>
      )
    }

  ]
}

function Index () {
  const [roles, setRoles] = useState([])
  const [selectedRoleIds, setSelectedRoleIds] = useState([])

  const [id, setId] = useState(null)
  const [name, setName] = useState(null)
  const [nameError, setNameError] = useState(null)

  const [modules, setModules] = useState([])
  const [modulesError, setModulesError] = useState(null)

  const [isLoading, setIsLoading] = useState(false)

  const nameHasError = !!nameError
  const modulesHasError = !!modulesError

  useEffect(() => {
    loadRoles()
  }, [])

  function loadRoles () {
    setSelectedRoleIds([])
    axios.get('/lararole/api/roles').then(response => {
      setRoles(response.data.roles)
    }).catch(error => {
      openNotification(error.response.data.message, error.response.data.description, 'error')
    })
  }

  const rowSelection = {
    onChange: (selectedRowKeys, selectedRows) => {
      setSelectedRoleIds(selectedRows.map((selectedRow) => {
        return {
          id: selectedRow.id
        }
      }))
    },
    onSelect: (record, selected, selectedRows) => {
      setSelectedRoleIds(selectedRows.map((selectedRow) => {
        return {
          id: selectedRow.id
        }
      }))
    },
    onSelectAll: (selected, selectedRows) => {
      setSelectedRoleIds(selectedRows.map((selectedRow) => {
        return {
          id: selectedRow.id
        }
      }))
    }
  }

  const hasSelected = selectedRoleIds.length > 0

  const [isVisibleCreateForm, setIsVisibleCreateForm] = useState(false)
  const [isVisibleEditForm, setIsVisibleEditForm] = useState(false)

  function showCreateForm () {
    axios.get('/lararole/api/modules')
      .then(response => {
        setModules(response.data.modules)
      })
      .catch(error => {
        openNotification(error.response.data.message, error.response.data.description, 'error')
      })
    setIsVisibleCreateForm(true)
  }

  function closeCreateForm () {
    setIsVisibleCreateForm(false)
    resetRoleData()
  }

  function closeEditForm () {
    setIsVisibleEditForm(false)
    resetRoleData()
  }

  function resetRoleData () {
    setId(null)
    setName(null)
    setModules(null)
    setNameError(null)
    setModulesError(null)
  }

  const openNotification = (message, description, type = 'success') => {
    if (type === 'success') {
      notification.success({
        message: message,
        description: description,
        placement: 'bottomLeft'
      })
    } else if (type === 'error') {
      notification.error({
        message: message,
        description: description,
        placement: 'bottomLeft'
      })
    }
  }

  return (
    <div>
      <Breadcrumb style={{ margin: '16px 0' }}>
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
            data: { roles: selectedRoleIds }
          })
            .then(response => {
              openNotification(response.data.message, response.data.description)
              loadRoles()
            })
            .catch(error => {
              openNotification(error.response.data.message, error.response.data.description, 'error')
            })
        }}
      >
        <Button type="danger" disabled={!hasSelected}>
          <DeleteOutlined/> Delete
        </Button>
      </Popconfirm>

      <Button type="primary" style={{ marginBottom: 16, marginLeft: 8 }} onClick={showCreateForm}>
        <PlusOutlined/> New Role
      </Button>

      <Drawer
        title="Create a new role"
        width={720}
        onClose={closeCreateForm}
        visible={isVisibleCreateForm}
        bodyStyle={{ paddingBottom: 80 }}
        footer={
          <div
            style={{
              textAlign: 'right'
            }}
          >
            <Button
              onClick={closeCreateForm}
              style={{ marginRight: 8 }}
            >
                            Cancel
            </Button>
            <Button loading={isLoading} onClick={() => {
              setIsLoading(true)
              const selectedModules = []

              function getModulesArray (modules) {
                for (let i = 0; i < modules.length; i++) {
                  if (modules[i].readChecked || modules[i].writeChecked || modules[i].readIndeterminate || modules[i].writeIndeterminate) {
                    selectedModules.push({
                      module_id: modules[i].id,
                      permission: modules[i].writeChecked ? 'write' : 'read'
                    })
                  }
                  if (modules[i].children) {
                    getModulesArray(modules[i].children)
                  }
                }
              }

              getModulesArray(modules)

              setNameError(null)
              setModulesError(null)

              axios.post('/lararole/api/role/create', {
                name,
                modules: selectedModules
              })
                .then(response => {
                  setIsLoading(false)
                  openNotification(response.data.message, response.data.description)
                  closeCreateForm()
                  loadRoles()
                })
                .catch(error => {
                  setIsLoading(false)
                  openNotification(error.response.data.message, error.response.data.description, 'error')

                  if (error.response.data.errors.name) {
                    setNameError(error.response.data.errors.name[0])
                  }
                  if (error.response.data.errors.modules) {
                    setModulesError(error.response.data.errors.modules[0])
                  }
                })
            }} type="primary">
                            Create Role
            </Button>
          </div>
        }
      >
        <Form layout="vertical">
          <Form.Item
            label="Role Name"
            validateStatus={nameHasError ? 'error' : null}
            help={nameHasError ? nameError : null}
          >
            <Input placeholder="Manager, Editor etc..." value={name}
              onChange={event => {
                setName(event.target.value)
              }}/>
          </Form.Item>

          <Form.Item
            label="Modules"
            validateStatus={modulesHasError ? 'error' : null}
            help={modulesHasError ? modulesError : null}
          >
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
        bodyStyle={{ paddingBottom: 80 }}
        footer={
          <div
            style={{
              textAlign: 'right'
            }}
          >
            <Button
              onClick={closeEditForm}
              style={{ marginRight: 8 }}
            >
                            Cancel
            </Button>
            <Button loading={isLoading} onClick={() => {
              setIsLoading(true)
              const selectedModules = []

              function getModulesArray (modules) {
                for (let i = 0; i < modules.length; i++) {
                  if (modules[i].readChecked || modules[i].writeChecked || modules[i].readIndeterminate || modules[i].writeIndeterminate) {
                    selectedModules.push({
                      module_id: modules[i].id,
                      permission: modules[i].writeChecked ? 'write' : 'read'
                    })
                  }
                  if (modules[i].children) {
                    getModulesArray(modules[i].children)
                  }
                }
              }

              getModulesArray(modules)

              setNameError(null)
              setModulesError(null)

              axios.put('/lararole/api/role/' + id + '/update', {
                name,
                modules: selectedModules
              })
                .then(response => {
                  setIsLoading(false)
                  openNotification(response.data.message, response.data.description)
                  closeEditForm()
                  loadRoles()
                })
                .catch(error => {
                  setIsLoading(false)
                  openNotification(error.response.data.message, error.response.data.description, 'error')

                  if (error.response.data.errors.name) {
                    setNameError(error.response.data.errors.name[0])
                  }
                  if (error.response.data.errors.modules) {
                    setModulesError(error.response.data.errors.modules[0])
                  }
                })
            }} type="primary">
                            Update Role
            </Button>
          </div>
        }
      >
        <Form layout="vertical">
          <Form.Item
            label="Module Name"
            validateStatus={nameHasError ? 'error' : null}
            help={nameHasError ? nameError : null}
          >
            <Input placeholder="Product Management, Order Processing etc..." value={name}
              onChange={event => {
                setName(event.target.value)
              }}/>
          </Form.Item>

          <Form.Item
            label="Modules"
            validateStatus={modulesHasError ? 'error' : null}
            help={modulesHasError ? modulesError : null}
          >
            <Table
              columns={moduleColumns(modules, setModules)}
              pagination={false}
              dataSource={modules}/>
          </Form.Item>
        </Form>

      </Drawer>

      <span style={{ marginLeft: 8 }}>
        {hasSelected ? `Selected ${selectedRoleIds.length} items` : ''}
      </span>

      <Table
        columns={columns(setIsVisibleEditForm, setId, setName, setModules, setRoles, openNotification)}
        rowSelection={rowSelection}
        dataSource={roles}/>
    </div>
  )
}

export default Index
