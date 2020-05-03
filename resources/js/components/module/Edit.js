import React, {useEffect, useState} from 'react';
import {Breadcrumb, Button, Form, Input, Layout, Tooltip} from 'antd';
import {DeploymentUnitOutlined, HomeOutlined, QuestionCircleOutlined} from '@ant-design/icons';
import {Link} from "react-router-dom";

const {Content} = Layout;

const formItemLayout = {
    labelCol: {
        xs: {
            span: 16,
        },
        sm: {
            span: 4,
        },
    },
    wrapperCol: {
        xs: {
            span: 24,
        },
        sm: {
            span: 16,
        },
    },
};

const tailFormItemLayout = {
    wrapperCol: {
        xs: {
            span: 24,
            offset: 0,
        },
        sm: {
            span: 16,
            offset: 4,
        },
    },
};

const EditModuleForm = (id) => {
    const [name, setName] = useState(null);
    const [alias, setAlias] = useState(null);
    const [icon, setIcon] = useState(null);

    useEffect(() => {
        axios.get('/lararole/api/module/' + id.id + '/edit').then((response) => {
            setName(response.data.module.name);
            setAlias(response.data.module.alias);
            setIcon(response.data.module.icon);
        });
    }, []);


    const [form] = Form.useForm();

    const onFinish = values => {
        axios.put('/lararole/api/module/' + id.id + '/update', {
            name: name,
            alias: alias
        }).then((response) => {
            window.location = "/lararole/module"
        })
    };

    return (
        <div>
            <Form
                {...formItemLayout}
                form={form}
                name="module"
                onFinish={onFinish}
            >
                <Form.Item label="Module Name" rules={[
                    {required: true, message: 'Please input you module name'}
                ]}>
                    <Input placeholder="Product Management, Order Processing etc..." value={name} allowClear
                           onChange={event => {
                               setName(event.target.value)
                           }}/>
                </Form.Item>

                <Form.Item
                    label={
                        <span>
                            Alias&nbsp;
                            <Tooltip title="What do you want to show alternate of module name?">
                                <QuestionCircleOutlined/>
                            </Tooltip>
                        </span>
                    }
                    rules={[
                        {
                            required: true,
                            message: 'Please input your module alias!',
                        },
                    ]}>
                    <Input placeholder="Product Management, Order Processing etc..." value={alias} allowClear
                           onChange={event => {
                               setAlias(event.target.value)
                           }}/>
                </Form.Item>

                <Form.Item
                    label="Icon"
                    rules={[
                        {
                            required: false,
                            message: 'Please input your icon name or icon path!',
                        },
                    ]}>
                    <Input placeholder="fa fa-users etc..." value={icon} allowClear
                           onChange={event => {
                               setIcon(event.target.value)
                           }}/>
                </Form.Item>

                <Form.Item {...tailFormItemLayout}>
                    <Button type="primary" htmlType="submit">
                        Update Module
                    </Button>
                </Form.Item>
            </Form>
        </div>

    );
};

function Edit(props) {
    const {id} = props.match.params;

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
                <Breadcrumb.Item>
                    Create
                </Breadcrumb.Item>
            </Breadcrumb>
            <Content className="site-layout-background" style={{
                padding: 24,
                margin: 0,
            }}>
                <EditModuleForm id={id}/>
            </Content>
        </div>
    );
}

export default Edit;
