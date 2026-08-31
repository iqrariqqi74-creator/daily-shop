<template>
    <section class="content">
        <div class="container-fluid">
            <!-- Loader -->
            <div
                class="d-flex align-items-center justify-content-center"
                v-if="isLoading"
            >
                <div>
                    <img width="100" :src="loaderImage" />
                    <p class="text-center mt-0">Please wait...</p>
                </div>
            </div>

            <div v-else>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card shadow my-5 mx-5 mb-4">
                            <div class="card-header py-3">
                                <div class="row">
                                    <div class="col-md-8">
                                        <h6
                                            class="m-0 font-weight-bold text-primary"
                                        >
                                            Create Product
                                        </h6>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="mb-1">
                                    <div class="col-md-12 my-4">
                                        <!-- Form Row 1 -->
                                        <div class="row">
                                            <!-- Category -->
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Category</label>
                                                    <v-select
                                                        v-model="
                                                            product.category_id
                                                        "
                                                        :options="categories"
                                                        label="name"
                                                        :reduce="
                                                            (category) =>
                                                                category.id
                                                        "
                                                        placeholder="Select Category"
                                                        :class="{
                                                            'is-invalid':
                                                                errorMessages.category_id,
                                                            'is-valid':
                                                                product.category_id &&
                                                                !errorMessages.category_id,
                                                        }"
                                                    >
                                                        <!-- Dropdown -->
                                                        <template
                                                            #option="{
                                                                name,
                                                                image,
                                                            }"
                                                        >
                                                            <div
                                                                class="d-flex align-items-center py-2"
                                                            >
                                                                <img
                                                                    :src="
                                                                        IMAGE_URL +
                                                                        image
                                                                    "
                                                                    width="40"
                                                                    height="40"
                                                                    class="rounded-circle me-3"
                                                                    style="
                                                                        object-fit: cover;
                                                                    "
                                                                />
                                                                <span>{{
                                                                    name
                                                                }}</span>
                                                            </div>
                                                        </template>

                                                        <!-- Selected -->
                                                        <template
                                                            #selected-option="{
                                                                name,
                                                                image,
                                                            }"
                                                        >
                                                            <div
                                                                class="d-flex align-items-center"
                                                            >
                                                                <img
                                                                    :src="
                                                                        IMAGE_URL +
                                                                        image
                                                                    "
                                                                    width="35"
                                                                    height="35"
                                                                    class="rounded-circle me-3"
                                                                    style="
                                                                        object-fit: cover;
                                                                    "
                                                                />
                                                                <span>{{
                                                                    name
                                                                }}</span>
                                                            </div>
                                                        </template>

                                                        <!-- No Options -->
                                                        <template #no-options>
                                                            No category found.
                                                        </template>
                                                    </v-select>

                                                    <small class="text-danger">
                                                        {{
                                                            errorMessages
                                                                .category_id?.[0]
                                                        }}
                                                    </small>
                                                </div>
                                            </div>
                                            <!-- Brand -->
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Brand</label>
                                                    <v-select
                                                        v-model="
                                                            product.brand_id
                                                        "
                                                        :options="brands"
                                                        label="name"
                                                        :reduce="
                                                            (brand) => brand.id
                                                        "
                                                        placeholder="Select Brand"
                                                        :class="{
                                                            'is-invalid':
                                                                errorMessages.brand_id,
                                                            'is-valid':
                                                                product.brand_id &&
                                                                !errorMessages.brand_id,
                                                        }"
                                                    >
                                                        <!-- Dropdown -->
                                                        <template
                                                            #option="{
                                                                name,
                                                                image,
                                                            }"
                                                        >
                                                            <div
                                                                class="d-flex align-items-center py-2"
                                                            >
                                                                <img
                                                                    :src="
                                                                        IMAGE_URL +
                                                                        image
                                                                    "
                                                                    width="40"
                                                                    height="40"
                                                                    class="rounded-circle me-3"
                                                                    style="
                                                                        object-fit: cover;
                                                                    "
                                                                />
                                                                <span>{{
                                                                    name
                                                                }}</span>
                                                            </div>
                                                        </template>

                                                        <!-- Selected -->
                                                        <template
                                                            #selected-option="{
                                                                name,
                                                                image,
                                                            }"
                                                        >
                                                            <div
                                                                class="d-flex align-items-center"
                                                            >
                                                                <img
                                                                    :src="
                                                                        IMAGE_URL +
                                                                        image
                                                                    "
                                                                    width="35"
                                                                    height="35"
                                                                    class="rounded-circle me-3"
                                                                    style="
                                                                        object-fit: cover;
                                                                    "
                                                                />
                                                                <span>{{
                                                                    name
                                                                }}</span>
                                                            </div>
                                                        </template>

                                                        <!-- No Options -->
                                                        <template #no-options>
                                                            No brand found.
                                                        </template>
                                                    </v-select>

                                                    <small class="text-danger">
                                                        {{
                                                            errorMessages
                                                                .brand_id?.[0]
                                                        }}
                                                    </small>
                                                </div>
                                            </div>

                                            <!-- Name -->
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Name</label>
                                                    <input
                                                        type="text"
                                                        class="form-control"
                                                        placeholder="Name"
                                                        v-model="product.name"
                                                        :class="{
                                                            'is-invalid':
                                                                errorMessages.name,
                                                            'is-valid':
                                                                product.name &&
                                                                !errorMessages.name,
                                                        }"
                                                    />
                                                    <small
                                                        class="text-danger"
                                                        >{{
                                                            errorMessages
                                                                .name?.[0]
                                                        }}</small
                                                    >
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Model</label>
                                                    <input
                                                        type="text"
                                                        class="form-control"
                                                        placeholder="Model"
                                                        v-model="product.model"
                                                        :class="{
                                                            'is-invalid':
                                                                errorMessages.model,
                                                            'is-valid':
                                                                product.model &&
                                                                !errorMessages.model,
                                                        }"
                                                    />
                                                    <small
                                                        class="text-danger"
                                                        >{{
                                                            errorMessages
                                                                .model?.[0]
                                                        }}</small
                                                    >
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Lead Time</label>
                                                    <input
                                                        type="text"
                                                        class="form-control"
                                                        placeholder="Lead Time"
                                                        v-model="
                                                            product.lead_time
                                                        "
                                                        :class="{
                                                            'is-invalid':
                                                                errorMessages.lead_time,
                                                            'is-valid':
                                                                product.lead_time &&
                                                                !errorMessages.lead_time,
                                                        }"
                                                    />
                                                    <small
                                                        class="text-danger"
                                                        >{{
                                                            errorMessages
                                                                .lead_time?.[0]
                                                        }}</small
                                                    >
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Taxes</label>
                                                    <v-select
                                                        v-model="product.tax_id"
                                                        :options="taxes"
                                                        label="name"
                                                        :reduce="
                                                            (tax) => tax.id
                                                        "
                                                        placeholder="Select Tax"
                                                        :class="{
                                                            'is-invalid':
                                                                errorMessages.tax_id,
                                                            'is-valid':
                                                                product.tax_id &&
                                                                !errorMessages.tax_id,
                                                        }"
                                                    >
                                                        <!-- Dropdown -->

                                                        <!-- No Options -->
                                                        <template #no-options>
                                                            No Tax found.
                                                        </template>
                                                    </v-select>

                                                    <small class="text-danger">
                                                        {{
                                                            errorMessages
                                                                .tax_id?.[0]
                                                        }}
                                                    </small>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Status</label>
                                                    <select
                                                        class="form-control"
                                                        v-model="product.status"
                                                        :class="{
                                                            'is-invalid':
                                                                errorMessages.status,
                                                            'is-valid':
                                                                product.status &&
                                                                !errorMessages.status,
                                                        }"
                                                    >
                                                        <option value="active">
                                                            Active
                                                        </option>
                                                        <option
                                                            value="inactive"
                                                        >
                                                            Inactive
                                                        </option>
                                                    </select>
                                                    <small
                                                        class="text-danger"
                                                        >{{
                                                            errorMessages
                                                                .status?.[0]
                                                        }}</small
                                                    >
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Promo</label>
                                                    <select
                                                        class="form-control"
                                                        v-model="
                                                            product.is_promo
                                                        "
                                                        :class="{
                                                            'is-invalid':
                                                                errorMessages.is_promo,
                                                            'is-valid':
                                                                product.is_promo &&
                                                                !errorMessages.is_promo,
                                                        }"
                                                    >
                                                        <option :value="1">
                                                            Yes
                                                        </option>
                                                        <option :value="0">
                                                            No
                                                        </option>
                                                    </select>
                                                    <small
                                                        class="text-danger"
                                                        >{{
                                                            errorMessages
                                                                .is_promo?.[0]
                                                        }}</small
                                                    >
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Featured</label>
                                                    <select
                                                        class="form-control"
                                                        v-model="
                                                            product.is_featured
                                                        "
                                                        :class="{
                                                            'is-invalid':
                                                                errorMessages.is_featured,
                                                            'is-valid':
                                                                product.is_featured &&
                                                                !errorMessages.is_featured,
                                                        }"
                                                    >
                                                        <option :value="1">
                                                            Yes
                                                        </option>
                                                        <option :value="0">
                                                            No
                                                        </option>
                                                    </select>
                                                    <small
                                                        class="text-danger"
                                                        >{{
                                                            errorMessages
                                                                .is_featured?.[0]
                                                        }}</small
                                                    >
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Discounted</label>
                                                    <select
                                                        class="form-control"
                                                        v-model="
                                                            product.is_discounted
                                                        "
                                                        :class="{
                                                            'is-invalid':
                                                                errorMessages.is_discounted,
                                                            'is-valid':
                                                                product.is_discounted &&
                                                                !errorMessages.is_discounted,
                                                        }"
                                                    >
                                                        <option :value="1">
                                                            Yes
                                                        </option>
                                                        <option :value="0">
                                                            No
                                                        </option>
                                                    </select>
                                                    <small
                                                        class="text-danger"
                                                        >{{
                                                            errorMessages
                                                                .is_discounted?.[0]
                                                        }}</small
                                                    >
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Trending</label>
                                                    <select
                                                        class="form-control"
                                                        v-model="
                                                            product.is_tranding
                                                        "
                                                        :class="{
                                                            'is-invalid':
                                                                errorMessages.is_discounted,
                                                            'is-valid':
                                                                product.is_discounted &&
                                                                !errorMessages.is_discounted,
                                                        }"
                                                    >
                                                        <option :value="1">
                                                            Yes
                                                        </option>
                                                        <option :value="0">
                                                            No
                                                        </option>
                                                    </select>
                                                    <small
                                                        class="text-danger"
                                                        >{{
                                                            errorMessages
                                                                .is_tranding?.[0]
                                                        }}</small
                                                    >
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label
                                                        >Short
                                                        Description</label
                                                    >
                                                    <ckeditor
                                                        :editor="editor"
                                                        v-model="
                                                            product.short_description
                                                        "
                                                    >
                                                    </ckeditor>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Description</label>
                                                    <ckeditor
                                                        :editor="editor"
                                                        v-model="
                                                            product.description
                                                        "
                                                    >
                                                    </ckeditor>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label
                                                        >Technical
                                                        Specification</label
                                                    >
                                                    <ckeditor
                                                        :editor="editor"
                                                        v-model="
                                                            product.technical_specification
                                                        "
                                                    >
                                                    </ckeditor>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Uses</label>
                                                    <ckeditor
                                                        :editor="editor"
                                                        v-model="product.uses"
                                                    >
                                                    </ckeditor>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Warranty</label>
                                                    <ckeditor
                                                        :editor="editor"
                                                        v-model="
                                                            product.warranty
                                                        "
                                                    >
                                                    </ckeditor>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Keywords</label>
                                                    <textarea
                                                        rows="4"
                                                        class="form-control"
                                                        v-model="
                                                            product.keywords
                                                        "
                                                    ></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Form Row 2: description & Image -->
                                        <div class="row">
                                            <!-- Image Upload -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="image"
                                                        >Product Image</label
                                                    >
                                                    <input
                                                        type="file"
                                                        class="form-control"
                                                        @change="
                                                            handleImageUpload
                                                        "
                                                        accept="image/*"
                                                        :class="{
                                                            'is-invalid':
                                                                errorMessages.image,
                                                            'is-valid':
                                                                product.image &&
                                                                !errorMessages.image,
                                                        }"
                                                    />
                                                    <small
                                                        class="text-danger"
                                                        >{{
                                                            errorMessages
                                                                .image?.[0]
                                                        }}</small
                                                    >

                                                    <!-- Preview -->
                                                    <div
                                                        v-if="
                                                            product.imagePreview
                                                        "
                                                        class="mt-2"
                                                    >
                                                        <img
                                                            :src="
                                                                product.imagePreview
                                                            "
                                                            alt="Preview"
                                                            width="120"
                                                            class="rounded shadow-sm"
                                                        />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row shadow">
                                        <div class="col-md-12">
                                            <div class="card mt-4">
                                                <div
                                                    class="card-header d-flex justify-content-between"
                                                >
                                                    <h5>Product Images</h5>
                                                    <button
                                                        type="button"
                                                        class="btn btn-primary btn-sm"
                                                        @click="addImageRow"
                                                    >
                                                        Add Image +
                                                    </button>
                                                </div>
                                                <div class="card-body">
                                                    <div
                                                        class="row mb-3"
                                                        v-for="(
                                                            image, index
                                                        ) in product.productImages"
                                                        :key="index"
                                                    >
                                                        <div class="col-md-4">
                                                            <input
                                                                type="file"
                                                                class="form-control"
                                                                @change="
                                                                    uploadProductImage(
                                                                        $event,
                                                                        index,
                                                                    )
                                                                "
                                                                :class="{
                                                                    'is-invalid':
                                                                        errorMessages[
                                                                            `productImages.${index}.image`
                                                                        ],
                                                                    'is-valid':
                                                                        product
                                                                            .productImages[
                                                                            index
                                                                        ]
                                                                            .image &&
                                                                        !errorMessages[
                                                                            `productImages.${index}.image`
                                                                        ],
                                                                }"
                                                            />
                                                            <small
                                                                class="text-danger"
                                                            >
                                                                {{
                                                                    errorMessages[
                                                                        `productImages.${index}.image`
                                                                    ]?.[0]
                                                                }}
                                                            </small>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <img
                                                                v-if="
                                                                    image.preview
                                                                "
                                                                :src="
                                                                    image.preview
                                                                "
                                                                width="70"
                                                            />
                                                        </div>
                                                        <div class="col-md-4">
                                                            <button
                                                                class="btn"
                                                                @click="
                                                                    removeImage(
                                                                        index,
                                                                    )
                                                                "
                                                            >
                                                                <i
                                                                    class="fas fa-trash text-danger"
                                                                ></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row shadow">
                                        <div class="col-md-12">
                                            <div class="card mt-4">
                                                <div
                                                    class="card-header d-flex justify-content-between"
                                                >
                                                    <h5>Product Attributes</h5>
                                                    <button
                                                        class="btn btn-success btn-sm"
                                                        @click="addAttribute"
                                                    >
                                                        Add Attribute
                                                    </button>
                                                </div>
                                                <div class="card-body">
                                                    <div
                                                        class="row mb-3"
                                                        v-for="(
                                                            attribute, index
                                                        ) in product.productAttribute"
                                                        :key="index"
                                                    >
                                                        <!-- Color -->
                                                        <div
                                                            class="col-md-4 my-2"
                                                        >
                                                            <v-select
                                                                v-model="
                                                                    attribute.color_id
                                                                "
                                                                :options="
                                                                    colors
                                                                "
                                                                label="name"
                                                                :reduce="
                                                                    (item) =>
                                                                        item.id
                                                                "
                                                                placeholder="Select Color"
                                                            >
                                                                <!-- Dropdown List -->
                                                                <template
                                                                    #option="{
                                                                        name,
                                                                        image,
                                                                    }"
                                                                >
                                                                    <div
                                                                        class="d-flex align-items-center"
                                                                    >
                                                                        <img
                                                                            :src="
                                                                                IMAGE_URL +
                                                                                image
                                                                            "
                                                                            width="35"
                                                                            height="35"
                                                                            class="rounded-circle me-2"
                                                                            style="
                                                                                object-fit: cover;
                                                                            "
                                                                        />
                                                                        <span
                                                                            >{{
                                                                                name
                                                                            }}
                                                                        </span>
                                                                    </div>
                                                                </template>

                                                                <!-- Selected Item -->
                                                                <template
                                                                    #selected-option="{
                                                                        name,
                                                                        image,
                                                                    }"
                                                                >
                                                                    <div
                                                                        class="d-flex align-items-center"
                                                                    >
                                                                        <img
                                                                            :src="
                                                                                IMAGE_URL +
                                                                                image
                                                                            "
                                                                            width="30"
                                                                            height="30"
                                                                            class="rounded-circle me-2"
                                                                            style="
                                                                                object-fit: cover;
                                                                            "
                                                                        />
                                                                        <span>{{
                                                                            name
                                                                        }}</span>
                                                                    </div>
                                                                </template>
                                                            </v-select>
                                                            <small
                                                                class="text-danger"
                                                            >
                                                                {{
                                                                    errorMessages[
                                                                        `productAttribute.${index}.color_id`
                                                                    ]?.[0]
                                                                }}
                                                            </small>
                                                        </div>
                                                        <!-- Size -->
                                                        <div
                                                            class="col-md-4 my-2"
                                                        >
                                                            <v-select
                                                                v-model="
                                                                    attribute.size_id
                                                                "
                                                                :options="sizes"
                                                                label="name"
                                                                :reduce="
                                                                    (item) =>
                                                                        item.id
                                                                "
                                                                placeholder="Select Size"
                                                            >
                                                                <template
                                                                    #option="{
                                                                        name,
                                                                        image,
                                                                    }"
                                                                >
                                                                    <div
                                                                        class="d-flex align-items-center"
                                                                    >
                                                                        <img
                                                                            :src="
                                                                                IMAGE_URL +
                                                                                image
                                                                            "
                                                                            width="35"
                                                                            height="35"
                                                                            class="rounded-circle me-3"
                                                                            style="
                                                                                object-fit: cover;
                                                                            "
                                                                        />
                                                                        <span
                                                                            class="fw-medium ms-2"
                                                                            >{{
                                                                                name
                                                                            }}</span
                                                                        >
                                                                    </div>
                                                                </template>

                                                                <template
                                                                    #selected-option="{
                                                                        name,
                                                                        image,
                                                                    }"
                                                                >
                                                                    <div
                                                                        class="d-flex align-items-center"
                                                                    >
                                                                        <img
                                                                            :src="
                                                                                IMAGE_URL +
                                                                                image
                                                                            "
                                                                            width="30"
                                                                            height="30"
                                                                            class="rounded-circle me-3"
                                                                            style="
                                                                                object-fit: cover;
                                                                            "
                                                                        />
                                                                        <span
                                                                            class="fw-medium ms-2"
                                                                            >{{
                                                                                name
                                                                            }}</span
                                                                        >
                                                                    </div>
                                                                </template>
                                                            </v-select>
                                                            <small
                                                                class="text-danger"
                                                            >
                                                                {{
                                                                    errorMessages[
                                                                        `productAttribute.${index}.size_id`
                                                                    ]?.[0]
                                                                }}
                                                            </small>
                                                        </div>
                                                        <!-- SKU -->
                                                        <div
                                                            class="col-md-4 my-2"
                                                        >
                                                            <input
                                                                class="form-control"
                                                                placeholder="SKU"
                                                                v-model="
                                                                    attribute.sku
                                                                "
                                                                :class="{
                                                                    'is-invalid':
                                                                        errorMessages[
                                                                            `productAttribute.${index}.sku`
                                                                        ],
                                                                    'is-valid':
                                                                        attribute.sku &&
                                                                        !errorMessages[
                                                                            `productAttribute.${index}.sku`
                                                                        ],
                                                                }"
                                                            />
                                                            <small
                                                                class="text-danger"
                                                            >
                                                                {{
                                                                    errorMessages[
                                                                        `productAttribute.${index}.sku`
                                                                    ]?.[0]
                                                                }}
                                                            </small>
                                                        </div>
                                                        <!-- price -->
                                                        <div
                                                            class="col-md-4 my-2"
                                                        >
                                                            <input
                                                                type="number"
                                                                class="form-control"
                                                                placeholder="Price"
                                                                v-model="
                                                                    attribute.price
                                                                "
                                                                :class="{
                                                                    'is-invalid':
                                                                        errorMessages[
                                                                            `productAttribute.${index}.price`
                                                                        ],
                                                                    'is-valid':
                                                                        attribute.price &&
                                                                        !errorMessages[
                                                                            `productAttribute.${index}.price`
                                                                        ],
                                                                }"
                                                            />
                                                            <small
                                                                class="text-danger"
                                                            >
                                                                {{
                                                                    errorMessages[
                                                                        `productAttribute.${index}.color_id`
                                                                    ]?.[0]
                                                                }}
                                                            </small>
                                                        </div>

                                                        <!-- Sale Price -->
                                                        <div
                                                            class="col-md-4 my-2"
                                                        >
                                                            <input
                                                                type="number"
                                                                class="form-control"
                                                                placeholder="Sale Price"
                                                                v-model="
                                                                    attribute.sale_price
                                                                "
                                                                :class="{
                                                                    'is-invalid':
                                                                        errorMessages[
                                                                            `productAttribute.${index}.sale_price`
                                                                        ],
                                                                    'is-valid':
                                                                        attribute.sale_price &&
                                                                        !errorMessages[
                                                                            `productAttribute.${index}.sale_price`
                                                                        ],
                                                                }"
                                                            />
                                                            <small
                                                                class="text-danger"
                                                            >
                                                                {{
                                                                    errorMessages[
                                                                        `productAttribute.${index}.sale_price`
                                                                    ]?.[0]
                                                                }}
                                                            </small>
                                                        </div>
                                                        <!-- Qty -->
                                                        <div
                                                            class="col-md-3 my-2"
                                                        >
                                                            <input
                                                                type="number"
                                                                class="form-control"
                                                                placeholder="Qty"
                                                                v-model="
                                                                    attribute.quantity
                                                                "
                                                                :class="{
                                                                    'is-invalid':
                                                                        errorMessages[
                                                                            `productAttribute.${index}.quantity`
                                                                        ],
                                                                    'is-valid':
                                                                        attribute.quantity &&
                                                                        !errorMessages[
                                                                            `productAttribute.${index}.quantity`
                                                                        ],
                                                                }"
                                                            />
                                                            <small
                                                                class="text-danger"
                                                            >
                                                                {{
                                                                    errorMessages[
                                                                        `productAttribute.${index}.quantity`
                                                                    ]?.[0]
                                                                }}
                                                            </small>
                                                        </div>
                                                        <!-- Delete -->
                                                        <div class="col-md-1">
                                                            <button
                                                                class="btn"
                                                                @click="
                                                                    removeAttribute(
                                                                        index,
                                                                    )
                                                                "
                                                            >
                                                                <i
                                                                    class="fas fa-trash text-danger"
                                                                ></i>
                                                            </button>
                                                        </div>
                                                        <!-- Attribute Image -->
                                                        <div
                                                            class="col-md-4 my-2"
                                                        >
                                                            <input
                                                                type="file"
                                                                class="form-control"
                                                                accept="image/*"
                                                                @change="
                                                                    uploadAttributeImage(
                                                                        $event,
                                                                        index,
                                                                    )
                                                                "
                                                                :class="{
                                                                    'is-invalid':
                                                                        errorMessages[
                                                                            `productAttribute.${index}.image`
                                                                        ],
                                                                    'is-valid':
                                                                        attribute.image &&
                                                                        !errorMessages[
                                                                            `productAttribute.${index}.image`
                                                                        ],
                                                                }"
                                                            />

                                                            <small
                                                                class="text-danger"
                                                            >
                                                                {{
                                                                    errorMessages[
                                                                        `productAttribute.${index}.image`
                                                                    ]?.[0]
                                                                }}
                                                            </small>

                                                            <div
                                                                v-if="
                                                                    attribute.preview
                                                                "
                                                                class="mt-2"
                                                            >
                                                                <img
                                                                    :src="
                                                                        attribute.preview
                                                                    "
                                                                    width="70"
                                                                    class="img-thumbnail"
                                                                />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Buttons -->
                                    <div class="mt-3">
                                        <button
                                            class="btn btn-success"
                                            @click="createProduct"
                                            :disabled="isLoading"
                                        >
                                            <span v-if="isLoading"
                                                >Saving...</span
                                            >
                                            <span v-else>Save</span>
                                        </button>
                                        <button
                                            class="btn btn-danger ml-3"
                                            @click="cancel"
                                        >
                                            Cancel
                                        </button>
                                    </div>

                                    <!-- Authentication/Error -->
                                    <div class="row mt-3">
                                        <div class="col-md-4">
                                            <small class="text-danger">{{
                                                authenticateError
                                            }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
import axios from "axios";
import { format } from "date-fns";
import ClassicEditor from "@ckeditor/ckeditor5-build-classic";

import vSelect from "vue-select";
import "vue-select/dist/vue-select.css";

export default {
    name: "productCreate",
    components: {
        vSelect,
    },
    data() {
        return {
            IMAGE_URL: "http://127.0.0.1:8000/",

            editor: ClassicEditor,
            errorMessages: {},
            authenticateError: "",
            categories: [],
            colors: [],
            sizes: [],
            brand: [],
            taxes: [],
            product: {
                category_id: "",
                brand_id: "",
                name: "",
                slug: "",
                model: "",
                short_description: "",
                description: "",
                keywords: "",
                technical_specification: "",
                uses: "",
                warranty: "",
                lead_time: "",
                tax_id: "",
                is_promo: 0,
                is_featured: 0,
                is_discounted: 0,
                is_tranding: 0,
                status: "active",
                image: "",
                productImages: [
                    {
                        image: "",
                        preview: "",
                    },
                ],
                productAttribute: [
                    {
                        color_id: "",
                        size_id: "",
                        sku: "",
                        price: "",
                        sale_price: "",
                        quantity: "",
                        image: "",
                        preview: "",
                    },
                ],
            },
            isLoading: false,
            loaderImage: "/images/Spinning arrows.gif",
        };
    },
    methods: {
        clearErrorMessages(field) {
            if (this.errorMessages[field]) this.errorMessages[field] = "";
            if (this.authenticateError) this.authenticateError = "";
        },
        handleDateValueChange(date, field) {
            if (date) {
                this.product[field] = format(
                    new Date(date),
                    "yyyy-MM-dd HH:mm:ss",
                );
            }
        },
        handleImageUpload(event) {
            const file = event.target.files[0];
            if (file) {
                this.product.image = file;
                this.product.imagePreview = URL.createObjectURL(file);
            }
        },
        uploadAttributeImage(event, index) {
            const file = event.target.files[0];

            if (!file) return;

            this.product.productAttribute[index].image = file;
            this.product.productAttribute[index].preview =
                URL.createObjectURL(file);
        },
        async createProduct() {
            this.isLoading = true;

            try {
                const formData = new FormData();

                // ==========================
                // Product Basic Information
                // ==========================

                formData.append("category_id", this.product.category_id);
                formData.append("brand_id", this.product.brand_id);
                formData.append("name", this.product.name);
                formData.append("slug", this.product.slug);
                formData.append("model", this.product.model);
                formData.append(
                    "short_description",
                    this.product.short_description,
                );
                formData.append("description", this.product.description);
                formData.append("keywords", this.product.keywords);
                formData.append(
                    "technical_specification",
                    this.product.technical_specification,
                );
                formData.append("uses", this.product.uses);
                formData.append("warranty", this.product.warranty);
                formData.append("lead_time", this.product.lead_time);
                formData.append("tax_id", this.product.tax_id);

                formData.append("is_promo", this.product.is_promo);
                formData.append("is_featured", this.product.is_featured);
                formData.append("is_discounted", this.product.is_discounted);
                formData.append("is_tranding", this.product.is_tranding);

                formData.append("status", this.product.status);

                // Main Image

                if (this.product.image) {
                    formData.append("image", this.product.image);
                }

                // ==========================
                // Product Images
                // ==========================

                this.product.productImages.forEach((item, index) => {
                    if (item.image) {
                        formData.append(
                            `productImages[${index}][image]`,
                            item.image,
                        );
                    }

                    formData.append(
                        `productImages[${index}][sort_order]`,
                        item.sort_order,
                    );

                    formData.append(
                        `productImages[${index}][is_default]`,
                        item.is_default,
                    );
                });

                // ==========================
                // Product Attributes
                // ==========================

                this.product.productAttribute.forEach((item, index) => {
                    formData.append(
                        `productAttribute[${index}][color_id]`,
                        item.color_id,
                    );

                    formData.append(
                        `productAttribute[${index}][size_id]`,
                        item.size_id,
                    );

                    formData.append(
                        `productAttribute[${index}][sku]`,
                        item.sku,
                    );

                    formData.append(
                        `productAttribute[${index}][price]`,
                        item.price,
                    );

                    formData.append(
                        `productAttribute[${index}][sale_price]`,
                        item.sale_price,
                    );

                    formData.append(
                        `productAttribute[${index}][quantity]`,
                        item.quantity,
                    );

                    if (item.image) {
                        formData.append(
                            `productAttribute[${index}][image]`,
                            item.image,
                        );
                    }
                });

                // ==========================
                // Save
                // ==========================

                const response = await axios.post("products", formData, {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                });

                if (response.data.status == "success") {
                    this.$toast.success(response.data.message);

                    this.$router.push("/admin/products");
                }
            } catch (error) {
                if (error.response?.status == 403) {
                    this.authenticateError = error.response.data.message;
                } else {
                    this.errorMessages = error.response?.data?.errors || {};

                    this.$toast.error(
                        error.response?.data?.message || "Something went wrong",
                    );
                }
            } finally {
                this.isLoading = false;
            }
        },
        addImageRow() {
            this.product.productImages.push({
                image: "",
                preview: "",
            });
        },
        removeImage(index) {
            this.product.productImages.splice(index, 1);
        },

        uploadProductImage(e, index) {
            const file = e.target.files[0];
            this.product.productImages[index].image = file;
            this.product.productImages[index].preview =
                URL.createObjectURL(file);
        },
        addAttribute() {
            this.product.productAttribute.push({
                color_id: "",
                size_id: "",
                sku: "",
                price: "",
                sale_price: "",
                quantity: "",
                image: "",
            });
        },

        removeAttribute(index) {
            this.product.productAttribute.splice(index, 1);
        },
        cancel() {
            this.$router.push("/admin/products");
        },

        async getOptions() {
            try {
                this.isLoading = true;
                await axios
                    .get("common_options", {
                        params: {
                            options: `category,brand,size,color,tax`,
                        },
                    })
                    .then((response) => {
                        this.categories = response.data.categories;
                        this.brands = response.data.brands;
                        this.sizes = response.data.sizes;
                        this.colors = response.data.colors;
                        this.taxes = response.data.taxes;
                        this.isLoading = false;
                    });
            } catch (error) {
                this.isLoading = true;
                this.$swal({
                    title: "Error!",
                    html: error.response.data.message,
                    icon: "error",
                    confirmButtonText: "Ok",
                });
                this.isLoading = false;
            }
        },
    },
    mounted() {
        this.getOptions();
    },
};
</script>

<style scoped>
.fas,
.text-info {
    cursor: pointer;
}
</style>
