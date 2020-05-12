import React, { useEffect, useState } from 'react'
import {
  Badge,
  Breadcrumb,
  Button,
  Descriptions,
  Drawer,
  Form,
  Input,
  Modal,
  notification,
  Popconfirm,
  Switch,
  Table,
  Tag,
  Tooltip,
  TreeSelect
} from 'antd'
import {
  CheckOutlined,
  CloseOutlined,
  DeleteOutlined,
  DeploymentUnitOutlined,
  EditOutlined,
  HomeOutlined,
  PlusOutlined
} from '@ant-design/icons'

import { Link } from 'react-router-dom'

const columns = (setIsVisibleCreateForm, setIsVisibleEditForm, setId, setName, setAlias, setIcon, setParentModuleId, setModules, openNotification) => {
  const [isLoadingOf, setIsLoadingOf] = useState(null)
  const [isShowDetailModal, setIsShowDetailModal] = useState(false)

  const [selectedModule, setSelectedModule] = useState({})
  const [selectedModuleRoleTags, setSelectedModuleRoleTags] = useState(null)

  function showDetailModal () {
    setIsShowDetailModal(true)
  }

  function hideDetailModal () {
    setIsShowDetailModal(false)
  }

  return [
    {
      title: 'Name',
      dataIndex: 'name',
      key: 'name',
      render: (text, record) => (
        <>
          <a onClick={function () {
            showDetailModal()
            setSelectedModule(record)
            const roleTags = record.roles.map(role => {
              const color = role.permission.permission === 'write' ? 'geekblue' : 'blue'
              return <>
                <Tooltip title={role.permission.permission}>
                  <Tag color={color}>{role.name}</Tag>
                </Tooltip>
              </>
            })

            setSelectedModuleRoleTags(roleTags)
          }}>{text}</a>
          <Modal
            centered
            visible={isShowDetailModal}
            onOk={hideDetailModal}
            onCancel={hideDetailModal}
            width={720}
          >
            <Descriptions title="Module Info" bordered>
              <Descriptions.Item label="Name" span={2}>{selectedModule.name}</Descriptions.Item>
              <Descriptions.Item label="Alias" span={2}>{selectedModule.alias}</Descriptions.Item>
              <Descriptions.Item label="Slug" span={2}>{selectedModule.slug}</Descriptions.Item>
              <Descriptions.Item label="Icon" span={2}>{selectedModule.icon}</Descriptions.Item>
              <Descriptions.Item label="Last Update" span={2}>{selectedModule.created_at}</Descriptions.Item>
              <Descriptions.Item label="Created" span={2}>{selectedModule.created_at}</Descriptions.Item>
              <Descriptions.Item label="Status" span={3}><Badge status={selectedModule.active ? 'processing' : 'error'} text={selectedModule.active ? 'RUNNING' : 'IDLE'} /></Descriptions.Item>
              <Descriptions.Item label="Roles" span={3}>{selectedModuleRoleTags}</Descriptions.Item>
            </Descriptions>
          </Modal>
        </>
      )
    },
    {
      title: 'Alias',
      dataIndex: 'alias',
      key: 'alias',
      render: (text, record) =>
        <>
          <strong>{text}</strong>
          <br/>
                ({record.slug})
        </>
    },
    {
      title: 'Active',
      key: 'active',
      render: (text, record) => (
        <Switch
          checkedChildren={<CheckOutlined />}
          unCheckedChildren={<CloseOutlined />}
          defaultChecked={!!record.active}
          loading={isLoadingOf === record.id}
          onChange={() => {
            setIsLoadingOf(record.id)
            axios.get('/lararole/api/module/' + record.id + '/toggle-active')
              .then(response => {
                setIsLoadingOf(null)
                openNotification(response.data.message, response.data.description)
                axios.get('/lararole/api/modules')
                  .then(rolesResponse => {
                    setModules(rolesResponse.data.modules)
                  }).catch(rolesError => {
                    openNotification(rolesError.response.data.message, rolesError.response.data.description, 'error')
                  })
              })
              .catch(error => {
                setIsLoadingOf(null)
                openNotification(error.response.data.message, error.response.data.description, 'error')
              })
          }}
        />
      )
    },
    {
      title: 'Last Update',
      dataIndex: 'updated_at',
      key: 'last_update'
    },
    {
      title: 'Created',
      dataIndex: 'created_at',
      key: 'created'
    },
    {
      title: '',
      key: 'action',
      render: (text, record) => (
        <span>
          <a style={{ marginRight: 16 }} onClick={function () {
            setIsVisibleCreateForm(true)
            setParentModuleId(record.id)
          }}>
            <PlusOutlined/> New Child
          </a>

          <a style={{ marginRight: 16 }} onClick={function () {
            setIsVisibleEditForm(true)
            axios.get('/lararole/api/module/' + record.id + '/edit')
              .then(response => {
                setId(response.data.module.id)
                setName(response.data.module.name)
                setAlias(response.data.module.alias)
                setIcon(response.data.module.icon)
                setParentModuleId(response.data.module.module_id)
              })
              .catch(error => {
                openNotification(error.response.data.message, error.response.data.description, 'error')
              })
          }}>
            <EditOutlined/> Edit
          </a>
          <Popconfirm
            title="Are you sure delete this module?"
            onConfirm={() => {
              axios.delete('/lararole/api/module/' + record.id + '/delete')
                .then(response => {
                  openNotification(response.data.message, response.data.description)

                  axios.get('/lararole/api/modules')
                    .then(modulesResponse => {
                      setModules(modulesResponse.data.modules)
                    })
                    .catch(modulesError => {
                      openNotification(modulesError.response.data.message, modulesError.response.data.description, 'error')
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

function Index () {
  const [modules, setModules] = useState([])
  const [parentModuleId, setParentModuleId] = useState(null)
  const [parentModuleIdError, setParentModuleIdError] = useState(null)
  const [selectedModuleIds, setSelectedModuleIds] = useState([])

  const [id, setId] = useState(null)
  const [name, setName] = useState(null)
  const [nameError, setNameError] = useState(null)
  const [alias, setAlias] = useState(null)
  const [aliasError, setAliasError] = useState(null)
  const [icon, setIcon] = useState(null)
  const [iconError, setIconError] = useState(null)

  const [isLoading, setIsLoading] = useState(false)
  const [isDataLoading, setIsDataLoading] = useState(false)

  const parentModuleIdHasError = !!parentModuleIdError
  const nameHasError = !!nameError
  const aliasHasError = !!aliasError
  const iconHasError = !!iconError

  useEffect(() => {
    loadModules()
  }, [])

  function loadModules () {
    setIsDataLoading(true)
    setSelectedModuleIds([])
    axios.get('/lararole/api/modules')
      .then(response => {
        setModules(response.data.modules)
        setIsDataLoading(false)
      })
      .catch(error => {
        setIsDataLoading(false)
        openNotification(error.response.data.message, error.response.data.description, 'error')
      })
  }

  const rowSelection = {
    onChange: (selectedRowKeys, selectedRows) => {
      setSelectedModuleIds(selectedRows.map((selectedRow) => {
        return {
          id: selectedRow.id
        }
      }))
    },
    onSelect: (record, selected, selectedRows) => {
      setSelectedModuleIds(selectedRows.map((selectedRow) => {
        return {
          id: selectedRow.id
        }
      }))
    },
    onSelectAll: (selected, selectedRows) => {
      setSelectedModuleIds(selectedRows.map((selectedRow) => {
        return {
          id: selectedRow.id
        }
      }))
    }
  }

  const hasSelected = selectedModuleIds.length > 0

  const [isVisibleCreateForm, setIsVisibleCreateForm] = useState(false)
  const [isVisibleEditForm, setIsVisibleEditForm] = useState(false)

  function showCreateForm () {
    setIsVisibleCreateForm(true)
  }

  function closeCreateForm () {
    setIsVisibleCreateForm(false)
    resetModuleData()
  }

  function closeEditForm () {
    setIsVisibleEditForm(false)
    resetModuleData()
  }

  function resetModuleData () {
    setId(null)
    setName(null)
    setAlias(null)
    setIcon(null)
    setParentModuleId(null)
    setParentModuleIdError(null)
    setNameError(null)
    setAliasError(null)
    setIconError(null)
  }

  function mapModulesTreeData () {
    return modules.map(module => {
      const object = {
        title: module.name,
        value: module.id,
        key: module.key
      }

      if (module.children) {
        object.children = formatModuleTreeChildren(module.children)
      }

      return object
    })
  }

  function formatModuleTreeChildren (modules) {
    return modules.map(module => {
      const object = {
        title: module.name,
        value: module.id,
        key: module.key
      }

      if (module.children) {
        object.children = formatModuleTreeChildren(module.children)
      }

      return object
    })
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
          <Link to="/lararole/module">
            <DeploymentUnitOutlined/> Module
          </Link>
        </Breadcrumb.Item>
      </Breadcrumb>
      <Popconfirm
        title="Are you sure delete this module?"
        onConfirm={() => {
          axios.delete('/lararole/api/modules/delete', {
            data: { modules: selectedModuleIds }
          })
            .then(response => {
              openNotification(response.data.message, response.data.description)
              loadModules()
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
        <PlusOutlined/> New Module
      </Button>

      <Drawer
        title="Create a new module"
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
              setParentModuleIdError(null)
              setNameError(null)
              setAliasError(null)
              setIconError(null)
              axios.post('/lararole/api/module/create', {
                module_id: parentModuleId,
                name,
                alias,
                icon
              })
                .then(response => {
                  setIsLoading(false)
                  openNotification(response.data.message, response.data.description)
                  closeCreateForm()
                  loadModules()
                })
                .catch(error => {
                  setIsLoading(false)
                  openNotification(error.response.data.message, error.response.data.description, 'error')

                  if (error.response.data.errors.module_id) {
                    setParentModuleIdError(error.response.data.errors.module_id[0])
                  }
                  if (error.response.data.errors.name) {
                    setNameError(error.response.data.errors.name[0])
                  }
                  if (error.response.data.errors.alias) {
                    setAliasError(error.response.data.errors.alias[0])
                  }
                  if (error.response.data.errors.icon) {
                    setIconError(error.response.data.errors.icon[0])
                  }
                })
            }} type="primary">
                            Create Module
            </Button>
          </div>
        }
      >
        <Form layout="vertical">
          <Form.Item
            label="Choose Parent Module"
            validateStatus={parentModuleIdHasError ? 'error' : null}
            help={parentModuleIdHasError ? parentModuleIdError : null}
          >
            <TreeSelect
              style={{ width: '100%' }}
              value={parentModuleId}
              dropdownStyle={{ maxHeight: 400, overflow: 'auto' }}
              treeData={mapModulesTreeData()}
              placeholder="Please select"
              onChange={(value) => {
                setParentModuleId(value)
              }}
            />
          </Form.Item>

          <Form.Item
            label="Module Name"
            validateStatus={nameHasError ? 'error' : null}
            help={nameHasError ? nameError : null}
          >
            <Input placeholder="Product Management, Order Processing etc..." value={name}
              onChange={event => {
                setName(event.target.value)
                setAlias(event.target.value)
              }}/>
          </Form.Item>

          <Form.Item
            label="Alias"
            validateStatus={aliasHasError ? 'error' : null}
            help={aliasHasError ? aliasError : null}
          >
            <Input placeholder="Product Management, Order Processing etc..." value={alias}
              onChange={event => {
                setAlias(event.target.value)
              }}/>
          </Form.Item>

          <Form.Item
            label="Icon"
            validateStatus={iconHasError ? 'error' : null}
            help={iconHasError ? iconError : null}
          >
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
              setParentModuleIdError(null)
              setNameError(null)
              setAliasError(null)
              setIconError(null)

              axios.put('/lararole/api/module/' + id + '/update', {
                module_id: parentModuleId,
                name,
                alias,
                icon
              })
                .then(response => {
                  setIsLoading(false)
                  openNotification(response.data.message, response.data.description)
                  closeEditForm()
                  loadModules()
                })
                .catch(error => {
                  setIsLoading(false)
                  openNotification(error.response.data.message, error.response.data.description, 'error')

                  if (error.response.data.errors.module_id) {
                    setParentModuleIdError(error.response.data.errors.module_id[0])
                  }
                  if (error.response.data.errors.name) {
                    setNameError(error.response.data.errors.name[0])
                  }
                  if (error.response.data.errors.alias) {
                    setAliasError(error.response.data.errors.alias[0])
                  }
                  if (error.response.data.errors.icon) {
                    setIconError(error.response.data.errors.icon[0])
                  }
                })
            }} type="primary">
                            Update Module
            </Button>
          </div>
        }
      >
        <Form layout="vertical">
          <Form.Item
            label="Choose Parent Module"
            validateStatus={parentModuleIdHasError ? 'error' : null}
            help={parentModuleIdHasError ? parentModuleIdError : null}
          >
            <TreeSelect
              style={{ width: '100%' }}
              value={parentModuleId}
              dropdownStyle={{ maxHeight: 400, overflow: 'auto' }}
              treeData={mapModulesTreeData()}
              placeholder="Please select"
              onChange={(value) => {
                setParentModuleId(value)
              }}
              allowClear
            />
          </Form.Item>

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
            label="Alias"
            validateStatus={aliasHasError ? 'error' : null}
            help={aliasHasError ? aliasError : null}
          >
            <Input placeholder="Product Management, Order Processing etc..." value={alias}
              onChange={event => {
                setAlias(event.target.value)
              }}/>
          </Form.Item>

          <Form.Item
            label="Icon"
            validateStatus={iconHasError ? 'error' : null}
            help={iconHasError ? iconError : null}
          >
            <Input placeholder="fa fa-users etc..." value={icon} onChange={event => {
              setIcon(event.target.value)
            }}/>
          </Form.Item>
        </Form>
      </Drawer>

      <span style={{ marginLeft: 8 }}>
        {hasSelected ? `Selected ${selectedModuleIds.length} items` : ''}
      </span>

      <Table
        columns={columns(setIsVisibleCreateForm, setIsVisibleEditForm, setId, setName, setAlias, setIcon, setParentModuleId, setModules, openNotification)}
        rowSelection={rowSelection}
        dataSource={modules}
        tableLayout="auto"
        loading={isDataLoading}
      />
    </div>
  )
}

export default Index
